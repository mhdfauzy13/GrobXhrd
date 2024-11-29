<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\ResignationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResignationRequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:resignationrequest.index')->only(['index']);
        $this->middleware('permission:resignationrequest.create')->only(['create', 'store']);
        $this->middleware('permission:resignationrequest.approver')->only(['approver', 'updateStatus']);
    }

    public function create()
    {
        $user = Auth::user();

        $existingRequest = ResignationRequest::where('user_id', $user->id)->first();

        if ($existingRequest) {
            return redirect()->route('resignationrequest.index')->with('error', 'You have already submitted a resignation request.');
        }
        $managers = User::whereHas('roles', function ($query) {
            $query->where('name', 'manager');
        })->get();
        return view('employee.resignationrequest.create', compact('managers', 'user'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'resign_date' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'reason' => 'required|string',
            'manager_id' => 'required|exists:users,user_id',
            'document' => 'nullable|mimes:pdf,doc,docx|max:10240',
        ]);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents/resignationrequest', 'public');
        }

        ResignationRequest::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'resign_date' => $request->resign_date,
            'reason' => $request->reason,
            'remarks' => $request->remarks,
            'manager_id' => $request->manager_id,
            'document' => $documentPath,
            'status' => 'pending',
        ]);

        return redirect()->route('resignationrequest.index')->with('success', 'Resignation request submitted successfully!');
    }




    public function index()
    {
        $resignations = ResignationRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.resignationrequest.index', compact('resignations'));
    }

    public function updateStatus(Request $request, $resignationrequest_id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $resignation = ResignationRequest::findOrFail($resignationrequest_id);

        if ($resignation->manager_id != Auth::id()) {
            return redirect()->route('resignationrequest.index')->with('error', 'You are not authorized to update this request.');
        }
        $resignation->status = $request->status;
        $resignation->save();
        return redirect()->route('resignationrequest.index')->with('success', 'Resignation request has been ' . $request->status);
    }

    public function approver()
    {
        $managerId = Auth::id();

        $resignationsPending = ResignationRequest::where('manager_id', $managerId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $resignationsProcessed = ResignationRequest::where('manager_id', $managerId)
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('employee.resignationrequest.approver', compact('resignationsPending', 'resignationsProcessed'));
    }
}
