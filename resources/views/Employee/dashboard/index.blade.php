@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="container">
        <div class="row">
            <!-- Kolom Kalender -->
            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Event Calendar</h3>
                    </div>
                    <div class="card-body">
                        <div id="calendar">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Tabel Attendance -->
            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Attendance</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Check-in</th>
                                    <th class="text-center">Check-out</th>
                                    <th class="text-center">Check-in Status</th>
                                    <th class="text-center">Check-out Status</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td class="text-center">{{ $attendance->created_at->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            {{ $attendance->check_in ? $attendance->check_in->format('H:i:s') : '-' }}</td>
                                        <td class="text-center">
                                            {{ $attendance->check_out ? $attendance->check_out->format('H:i:s') : '-' }}
                                        </td>
                                        <td class="text-center"><span
                                                class="badge badge-{{ $attendance->check_in_status === 'IN' ? 'success' : 'danger' }}">
                                                {{ $attendance->check_in_status }}
                                            </span></td>
                                        <td class="text-center"><span
                                                class="badge badge-{{ $attendance->check_out_status === 'IN' ? 'success' : 'danger' }}">
                                                {{ $attendance->check_out_status }}
                                            </span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Recent Off Requests</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($offrequests as $offrequest)
                                    <tr>
                                        <td>{{ $offrequest->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge {{ $offrequest->status == 'approved' ? 'bg-success' : ($offrequest->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                                {{ ucfirst($offrequest->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('offrequests.show', $offrequest->id) }}" class="text-muted">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No recent off requests.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.15/index.global.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                events: '{{ route('employee.events.list') }}',
                editable: false,
                droppable: false,
                contentHeight: 'auto',
                height: 'auto',
                aspectRatio: 1.5,
            });

            calendar.render();

            // Event Listener untuk tombol "Next"
            document.getElementById('nextButton').addEventListener('click', function() {
                calendar.next(); // Pindah ke bulan selanjutnya
            });
        });
    </script>

    <style>
        /* Ensures the calendar fills its container and adjusts dynamically */
        #calendar {
            width: 100%;
            margin: 0 auto;
        }

        /* Menghilangkan garis bawah dari semua link (<a>) di seluruh halaman */
        a {
            text-decoration: none !important;
        }
    </style>


@endsection
