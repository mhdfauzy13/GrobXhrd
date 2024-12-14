<!-- View untuk halaman Index Submit Resignation -->
@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($resignationRequests->isEmpty())
            <p>No resignation requests available.</p>
        @else
            <div class="card">
                <div class="card-header">
                    <h4>Submit Resignation Requests</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Resignation Date</th>
                                <th>Reason</th>
                                <th>Remarks</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resignationRequests as $request)
                                <tr>
                                    <td>{{ $request->employee->first_name }} {{ $request->employee->last_name }}</td>
                                    <td>{{ $request->resign_date }}</td>
                                    <td>{{ $request->reason }}</td>
                                    <td>{{ $request->remarks }}</td>
                                    <td>{{ ucfirst($request->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <a href="{{ route('submitresign.create') }}" class="btn btn-primary mt-4">Create Resignation</a>
    </div>
@endsection
