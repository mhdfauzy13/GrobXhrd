@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Resignation Requests</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($resignations->isEmpty())
                    <p class="text-center">No pending resignation requests.</p>
                    @can('resignationrequest.create')
                        <div class="text-center mt-3">
                            <a href="{{ route('resignationrequest.create') }}" class="btn btn-primary">
                                Create Resignation Request
                            </a>
                        </div>
                    @endcan
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Resign Date</th>
                                <th>Reason</th>
                                <th>Remarks</th>
                                <th>Document</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resignations as $resignation)
                                <tr>
                                    <td>{{ $resignation->name }}</td>
                                    <td>{{ $resignation->resign_date->format('d M Y') }}</td>
                                    <td>{{ $resignation->reason }}</td>
                                    <td>{{ $resignation->remarks ?? '-' }}</td>
                                    <td>
                                        @if ($resignation->document)
                                            <a href="{{ asset('storage/' . $resignation->document) }}"
                                                target="_blank">View</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $resignation->status == 'pending' ? 'bg-warning' : ($resignation->status == 'approved' ? 'bg-success' : 'bg-danger') }}">
                                            {{ ucfirst($resignation->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
