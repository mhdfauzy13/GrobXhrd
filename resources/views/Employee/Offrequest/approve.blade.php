@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Off Request List</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (Auth::user()->unreadNotifications->count())
                    <div class="alert alert-info">
                        Anda memiliki {{ Auth::user()->unreadNotifications->count() }} notifikasi baru.
                    </div>
                @endif

                <ul>
                    @foreach (Auth::user()->notifications as $notification)
                        <li>
                            {{ $notification->data['message'] }}
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>

                @if ($offrequests->isEmpty())
                    <div class="alert alert-info" role="alert">
                        Tidak ada pengajuan cuti yang perlu disetujui saat ini.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Start Event</th>
                                    <th>End Event</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($offrequests as $offrequest)
                                    <tr>
                                        <td>{{ $offrequest->user->name ?? 'N/A' }}</td>
                                        <td>{{ $offrequest->user->email ?? 'N/A' }}</td>
                                        <td>{{ $offrequest->title }}</td>
                                        <td>{{ $offrequest->description }}</td>
                                        <td>{{ $offrequest->start_event ? $offrequest->start_event->format('Y-m-d') : 'N/A' }}
                                        </td>
                                        <td>{{ $offrequest->end_event ? $offrequest->end_event->format('Y-m-d') : 'N/A' }}
                                        </td>
                                        <td>{{ ucfirst($offrequest->status) }}</td>
                                        <td class="d-flex">
                                            @can('offrequest.approver')
                                                <form action="{{ route('offrequest.approve', $offrequest->offrequest_id) }}"
                                                    method="POST" class="mr-2">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                </form>

                                                <form action="{{ route('offrequest.reject', $offrequest->offrequest_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <hr>

                <h4>Riwayat Pengajuan Cuti</h4>
                @if ($approvedRequests->isEmpty())
                    <div class="alert alert-info" role="alert">
                        Tidak ada riwayat pengajuan cuti.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Start Event</th>
                                    <th>End Event</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($approvedRequests as $approvedRequest)
                                    <tr>
                                        <td>{{ $approvedRequest->user->name ?? 'N/A' }}</td>
                                        <td>{{ $approvedRequest->user->email ?? 'N/A' }}</td>
                                        <td>{{ $approvedRequest->title }}</td>
                                        <td>{{ $approvedRequest->description }}</td>
                                        <td>{{ $approvedRequest->start_event ? $approvedRequest->start_event->format('Y-m-d') : 'N/A' }}
                                        </td>
                                        <td>{{ $approvedRequest->end_event ? $approvedRequest->end_event->format('Y-m-d') : 'N/A' }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $approvedRequest->status == 'approved' ? 'bg-success' : ($approvedRequest->status == 'rejected' ? 'bg-danger' : 'bg-secondary') }}">
                                                {{ ucfirst($approvedRequest->status) }}
                                            </span>
                                        </td>                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </section>
@endsection
