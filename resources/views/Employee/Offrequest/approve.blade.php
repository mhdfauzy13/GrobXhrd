@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Off Request List </h3>
            </div>
            <div class="card-body">
                @if($offrequests->isEmpty())
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
                                        <td>{{ $offrequest->start_event ? $offrequest->start_event->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ $offrequest->end_event ? $offrequest->end_event->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ ucfirst($offrequest->status) }}</td>
                                        <td class="d-flex">
                                            <!-- Approve Button -->
                                            <form action="{{ route('offrequest.approve', $offrequest->id) }}" method="POST" class="mr-2">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>

                                            <!-- Reject Button -->
                                            <form action="{{ route('offrequest.reject', $offrequest->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
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
@endsection
