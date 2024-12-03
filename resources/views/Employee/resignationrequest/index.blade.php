@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Card Header -->
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

                <!-- Button Create or My Requests -->
                @if ($role === 'employee' || $role === 'superadmin')
                    <div class="card">
                        <div class="card-header">
                            <h5>Your Resignation Requests</h5>
                        </div>
                        <div class="card-body">
                            @if ($resignations->isEmpty())
                                <p class="text-center">You have not submitted any resignation requests.</p>
                            @else
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Resign Date</th>
                                            <th>Reason</th>
                                            <th>Remarks</th>
                                            <th>Document</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($resignations as $request)
                                            <tr>
                                                <td>{{ $request->resign_date->format('d M Y') }}</td>
                                                <td>{{ $request->reason }}</td>
                                                <td>{{ $request->remarks ?? '-' }}</td>
                                                <td>
                                                    @if ($request->document)
                                                        <a href="{{ asset('storage/' . $request->document) }}"
                                                            target="_blank">View</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $request->status === 'pending' ? 'bg-warning' : ($request->status === 'approved' ? 'bg-success' : 'bg-danger') }}">
                                                        {{ ucfirst($request->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                            @if ($resignations->isEmpty() || $resignations->first()->status === 'rejected')
                                @can('resignationrequest.create')
                                    <div class="text-center mt-3">
                                        <a href="{{ route('resignationrequest.create') }}" class="btn btn-primary">
                                            Create Resignation Request
                                        </a>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </div>
                @endif

                @if (!$resignationsProcessed->isEmpty())
                    <div class="card">
                        <div class="card-header bg-success">
                            <h5 class="mb-0">Processed Requests</h5>
                        </div>
                        <div class="card-body">
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
                                    @foreach ($resignationsProcessed as $request)
                                        <tr>
                                            <td>{{ $request->name }}</td>
                                            <td>{{ $request->resign_date->format('d M Y') }}</td>
                                            <td>{{ $request->reason }}</td>
                                            <td>{{ $request->remarks ?? '-' }}</td>
                                            <td>
                                                @if ($request->document)
                                                    <a href="{{ asset('storage/' . $request->document) }}"
                                                        target="_blank">View</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $request->status === 'approved' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
