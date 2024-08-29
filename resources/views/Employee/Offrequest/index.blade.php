@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Off Requests List</h3>
                <div class="card-tools">
                    <a class="btn btn-primary btn-sm" href="{{ route('offrequest.create') }}">
                        <i class="fas fa-plus"></i> Add
                    </a>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Manager</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Start Event</th>
                                <th>End Event</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($offrequests as $offrequest)
                                <tr>
                                    <td>{{ $offrequest->user->name ?? 'N/A' }}</td>
                                    <td>{{ $offrequest->user->email ?? 'N/A' }}</td>
                                    <td>{{ $offrequest->manager->name ?? 'N/A' }}</td>
                                    <td>{{ $offrequest->title }}</td>
                                    <td>{{ $offrequest->description }}</td>
                                    <td>{{ $offrequest->start_event->format('Y-m-d') }}</td> <!-- Format tanggal sesuai kebutuhan -->
                                    <td>{{ $offrequest->end_event->format('Y-m-d') }}</td> <!-- Format tanggal sesuai kebutuhan -->
                                    <td>{{ ucfirst($offrequest->status) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No off requests available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
