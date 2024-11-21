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

                @if ($offrequests->isEmpty())
                    <div class="alert alert-info" role="alert">
                        No leave applications need to be approved at this time.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Start Event</th>
                                    <th>End Event</th>
                                    <th>Status</th>
                                    <th>Picture Proof</th>
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
                                        <td>
                                            @if ($offrequest->image)
                                                <img src="{{ asset('uploads/' . $offrequest->image) }}" alt="Bukti Gambar"
                                                    style="max-width: 100px; cursor: pointer;" data-toggle="modal"
                                                    data-target="#imageModal{{ $offrequest->offrequest_id }}">
                                                <!-- Modal Trigger -->
                                                <div class="modal fade" id="imageModal{{ $offrequest->offrequest_id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="imageModalLabel{{ $offrequest->offrequest_id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="imageModalLabel{{ $offrequest->offrequest_id }}">
                                                                    Picture Proof</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <img src="{{ asset('uploads/' . $offrequest->image) }}"
                                                                    alt="Bukti Gambar" class="img-fluid"
                                                                    style="max-width: 100%; height: auto;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                N/A
                                            @endif
                                        </td>
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

                <h4>Leave Application History</h4>
                @if ($approvedRequests->isEmpty())
                    <div class="alert alert-info" role="alert">
                        There is no history of leave applications.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Start Event</th>
                                    <th>End Event</th>
                                    <th>Status</th>
                                    <th>Picture Proof</th>
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
                                            <span
                                                class="badge {{ $approvedRequest->status == 'approved' ? 'bg-success' : ($approvedRequest->status == 'rejected' ? 'bg-danger' : 'bg-secondary') }}">
                                                {{ ucfirst($approvedRequest->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($approvedRequest->image)
                                                <img src="{{ asset('uploads/' . $approvedRequest->image) }}"
                                                    alt="Bukti Gambar" style="max-width: 100px; cursor: pointer;"
                                                    data-toggle="modal"
                                                    data-target="#imageModalApproved{{ $approvedRequest->offrequest_id }}">
                                                <!-- Modal Trigger -->
                                                <div class="modal fade"
                                                    id="imageModalApproved{{ $approvedRequest->offrequest_id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="imageModalApprovedLabel{{ $approvedRequest->offrequest_id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="imageModalApprovedLabel{{ $approvedRequest->offrequest_id }}">
                                                                    Picture Proof</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <img src="{{ asset('uploads/' . $approvedRequest->image) }}"
                                                                    alt="Bukti Gambar" class="img-fluid"
                                                                    style="max-width: 100%; height: auto;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    @endpush
@endsection
