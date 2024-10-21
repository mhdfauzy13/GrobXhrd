@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h3 class="card-title">Rekap Absensi {{ $employee->first_name }} {{ $employee->last_name }}</h3>

                <div class="card-tools">
                    <form method="GET" action="{{ route('attendance.recap', ['employee_id' => $employee->employee_id]) }}" class="form-inline">
                        <div class="form-group mr-2">
                            <input type="month" name="month" class="form-control" value="{{ $month }}" style="border-radius: 10px;">
                        </div>
                        <button type="submit" class="btn btn-light" style="border-radius: 10px; padding: 5px 15px;">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover" style="border-radius: 10px; overflow: hidden;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->created_at->format('Y-m-d') }}</td>
                                <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i:s') : '-' }}</td>
                                <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i:s') : '-' }}</td>
                                
                                <td>
                                    @if ($attendance->check_in_status && !$attendance->check_out)
                                        {{ $attendance->check_in_status }}
                                    @elseif ($attendance->check_in_status && $attendance->check_out_status)
                                        {{ $attendance->check_in_status }} / {{ $attendance->check_out_status }}
                                    @elseif (!$attendance->check_in && !$attendance->check_out)
                                        Absent
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Rekap Total Statistik -->
                <div class="mt-4">
                    <h5>Rekap Total untuk Bulan: {{ $month }}</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Present
                            <span class="badge badge-primary badge-pill">{{ $totalPresent }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Late
                            <span class="badge badge-warning badge-pill">{{ $totalLate }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Early
                            <span class="badge badge-info badge-pill">{{ $totalEarly }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Absent
                            <span class="badge badge-danger badge-pill">{{ $totalAbsent }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
