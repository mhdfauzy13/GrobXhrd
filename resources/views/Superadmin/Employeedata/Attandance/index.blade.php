@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Attendance</h3>

                <div class="card-tools">
                    <form method="GET" action="{{ route('attandance.index') }}" class="form-inline">
                        <div class="form-group mr-2">
                            <input type="date" name="date" class="form-control" value="{{ $date }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 30%">Name</th>
                                <th style="width: 30%">Check in</th>
                                <th style="width: 30%">Check out</th>
                                <th style="width: 30%" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</td>
                                    <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i:s') : '-' }}</td>
                                    <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i:s') : '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $attendance->status === 'IN' ? 'success' : 'danger' }}">
                                            {{ $attendance->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No data available for selected date.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
