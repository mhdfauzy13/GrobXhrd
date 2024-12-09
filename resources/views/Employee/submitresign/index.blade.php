@extends('layouts.app')

@section('content')
    <div class="container">

        @if ($resignationRequests->isEmpty())
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
                                    <!-- Memeriksa jika employee ada sebelum menampilkan nama -->
                                    <td>{{ $request->employee ? $request->employee->first_name . ' ' . $request->employee->last_name : 'No employee found' }}
                                    </td>
                                    <td>{{ $request->resign_date }}</td>
                                    <td>{{ $request->reason }}</td>
                                    <td>{{ $request->remarks }}</td>
                                    <td>{{ ucfirst($request->status) }}</td> <!-- Status dengan kapital pertama -->
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
