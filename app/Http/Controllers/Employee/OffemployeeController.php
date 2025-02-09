<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Mail\OffRequestStatusMail;
use App\Models\Offrequest;
use App\Models\User;
use App\Notifications\OffRequestEmailNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class OffemployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:offrequest.index')->only(['index', 'uploadImage', 'update']);
        $this->middleware('permission:offrequest.create')->only(['create', 'store']);
        $this->middleware('permission:offrequest.approver')->only(['approverIndex', 'approve', 'reject']);
    }

    public function index()
    {
        $totals = Offrequest::select('title', DB::raw('SUM(DATEDIFF(end_event, start_event) + 1) as total_days'))
            ->where('user_id', auth()->user()->user_id) 
            ->where('status', 'approved')
            ->groupBy('title')
            ->get();

        $offrequests = Offrequest::with(['user', 'manager'])
            ->where('user_id', auth()->user()->user_id)
            ->paginate(10);

        return view('Employee.Offrequest.index', compact('offrequests', 'totals'));
    }

    public function create()
    {
        $approvers = User::permission('offrequest.approver')->get();
        return view('Employee.Offrequest.create', compact('approvers'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|in:Sick,Holiday,Family Matters,Absence,Personal Time',
            'description' => 'required|string',
            'start_event' => 'required|date|after_or_equal:today',
            'end_event' => 'required|date|after_or_equal:start_event',
            'manager_id' => 'nullable|exists:users,user_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // image nullable
        ]);

        $user = Auth::user();
        $currentTime = now(); // Mendapatkan waktu saat ini

        // Mengecek apakah pengajuan dilakukan setelah jam 9 malam
        if ($currentTime->hour >= 21) {
            // Jika pengajuan dilakukan setelah jam 9 malam, maka start_event hanya bisa untuk hari berikutnya
            if ($request->start_event == now()->toDateString()) {
                return redirect()->route('offrequest.index')->with('error', 'Leave requests cannot be made on the same day after 9 PM.');
            }
        }

        // Cek apakah ada cuti yang tumpang tindih
        $existingRequest = Offrequest::where('user_id', $user->user_id)
            ->where(function ($query) use ($request) {
                $query->where('start_event', '<=', $request->end_event)->where('end_event', '>=', $request->start_event);
            })
            ->whereIn('status', ['approved', 'pending'])
            ->first();

        if ($existingRequest) {
            $message = $existingRequest->status === 'pending' ? 'You already have a leave request that is being processed.' : 'You already have an approved leave request.';
            return redirect()->route('offrequest.index')->with('error', $message);
        }

        $imageName = null;

        // Jika ada gambar yang di-upload
        if ($request->hasFile('image')) {
            $imageName = $this->uploadImage($request); // Panggil fungsi uploadImage untuk meng-upload gambar
        }

        // Simpan data pengajuan cuti tanpa gambar jika tidak ada gambar yang di-upload
        $offrequest = Offrequest::create([
            'user_id' => $user->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'manager_id' => $request->manager_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_event' => $request->start_event,
            'end_event' => $request->end_event,
            'status' => 'pending',
            'image' => $imageName, // Menyimpan gambar jika ada
        ]);

        // Kirim notifikasi ke manager
        $managers = User::role('manager')->get();
        foreach ($managers as $manager) {
            $manager->notify(new OffRequestEmailNotification($offrequest));
        }

        return redirect()->route('offrequest.index')->with('success', 'The Off Request has been successfully submitted');
    }

    public function edit($offrequest_id)
    {
        // Mencari offrequest berdasarkan ID
        $offrequest = Offrequest::findOrFail($offrequest_id);

        // Mengembalikan view edit dengan data offrequest
        return view('Employee.Offrequest.edit', compact('offrequest'));
    }

    public function update(Request $request, $offrequest_id)
    {
        // Validasi hanya untuk kolom image, kolom lain tetap readonly
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar (opsional)
        ]);

        // Mencari offrequest berdasarkan ID
        $offrequest = Offrequest::findOrFail($offrequest_id);

        // Jika ada gambar baru yang diunggah, proses gambar tersebut
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($offrequest->image) {
                Storage::delete('public/uploads/' . $offrequest->image); // Hapus gambar lama
            }

            // Simpan gambar baru menggunakan helper yang sama seperti di fungsi store
            $imageName = $this->uploadImage($request); // Menggunakan fungsi uploadImage yang sudah ada
            $offrequest->update(['image' => $imageName]); // Update dengan nama gambar baru
        }

        // Redirect ke halaman index offrequest dengan pesan sukses
        return redirect()->route('offrequest.index')->with('success', 'Offrequest image has been successfully updated.');
    }

    public function uploadImage(Request $request)
    {
        // Validasi gambar
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload gambar dan simpan namanya
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $imageName);

        return $imageName;
    }

    public function approverIndex(Request $request)
    {
        $filterDate = $request->input('filter_date');

        $offrequests = Offrequest::where('status', 'pending')
            ->when($filterDate, function ($query) use ($filterDate) {
                return $query->whereDate('start_event', $filterDate);
            })
            ->get();

        $approvedRequests = Offrequest::where('status', '!=', 'pending')
            ->when($filterDate, function ($query) use ($filterDate) {
                return $query->whereDate('start_event', $filterDate);
            })
            ->get();

        return view('Employee.Offrequest.approve', compact('offrequests', 'approvedRequests'));
    }

    public function approve($id)
    {
        $offrequest = Offrequest::findOrFail($id);

        $offrequest->update([
            'status' => 'approved',
            'approved_by' => auth()->user()->name,
        ]);

        Mail::to($offrequest->user->email)->send(new OffRequestStatusMail($offrequest, 'approved'));

        try {
            // Konfigurasi Google Client
            $client = new Google_Client();
            $client->setApplicationName('Grobmart HRD App');
            $client->setAuthConfig(storage_path('app/credentials/request-off-calendar-7d2f6a27c09a.json'));
            $client->addScope(Google_Service_Calendar::CALENDAR);
            $client->useApplicationDefaultCredentials();
            $client->setAccessType('offline');

            // Buat Service Google Calendar
            $service = new Google_Service_Calendar($client);

            // Ambil nama user dari relasi
            $userName = $offrequest->user ? $offrequest->user->name : 'Unknown User';

            // Data Event
            $event = new Google_Service_Calendar_Event([
                'summary' => 'Off - ' . $userName,
                'description' => 'Leave Type: ' . $offrequest->title . "\n" . 'Description: ' . $offrequest->description,
                'start' => [
                    'dateTime' => Carbon::parse($offrequest->start_event)->format('Y-m-d\TH:i:sP'),
                    'timeZone' => 'Asia/Jakarta',
                ],
                'end' => [
                    'dateTime' => Carbon::parse($offrequest->end_event)->format('Y-m-d\TH:i:sP'),
                    'timeZone' => 'Asia/Jakarta',
                ],
            ]);

            // Masukkan ID Kalender secara langsung
            $calendarId = 'grobmart.com_5pribbb1eta6qmfss7ouhbh8s8@group.calendar.google.com';

            // Masukkan Event ke Google Calendar
            $service->events->insert($calendarId, $event);

            return redirect()->route('offrequest.approver')->with('success', 'The Off Request has been successfully approved and added to Google Calendar.');
        } catch (\Exception $e) {
            return redirect()
                ->route('offrequest.approver')
                ->with('error', 'The Off Request was approved but could not be added to Google Calendar. Error: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $offrequest = Offrequest::findOrFail($id);

        // Ubah status pengajuan menjadi 'rejected' dan simpan nama approver
        $offrequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->user()->name,
        ]);

        Mail::to($offrequest->user->email)->send(new OffRequestStatusMail($offrequest, 'rejected'));

        return redirect()->route('offrequest.approver')->with('success', 'The Off Request has been successfully rejected.');
    }
}
