@extends('layouts.app')

@section('content')
    <h1>HELLO THIS IS THE EMPLOYEE DASHBOARD</h1>

    <div class="container">
        <div class="row">
            <!-- Kalender Container (Mengambil setengah lebar layar yang sama dengan tabel) -->
            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Event Calendar</h3>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <!-- Tabel Leave Requests (Mengambil setengah lebar layar) -->
            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Leave Requests</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle table-sm">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($offrequests as $offrequest)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('dist/img/default-150x150.png') }}" 
                                                 alt="Request" 
                                                 class="img-circle img-size-32 mr-2">
                                            {{ $offrequest->title }}
                                        </td>
                                        <td>{{ $offrequest->start_event }}</td>
                                        <td>{{ $offrequest->end_event }}</td>
                                        <td>
                                            <small class="
                                                @if ($offrequest->status == 'approved') text-success 
                                                @elseif ($offrequest->status == 'pending') text-warning 
                                                @else text-danger @endif
                                                mr-1">
                                                <i class="fas 
                                                    @if ($offrequest->status == 'approved') fa-check-circle 
                                                    @elseif ($offrequest->status == 'pending') fa-hourglass-half 
                                                    @else fa-times-circle @endif">
                                                </i>
                                                {{ ucfirst($offrequest->status) }}
                                            </small>
                                        </td>
                                        <td>
                                            <a href="{{ route('offrequest.show', $offrequest->id) }}" class="text-muted">
                                                <i class="fas fa-search"></i>
                                            </a>
                                        </td>   
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No leave requests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- FullCalendar dependencies -->
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
    </style>
@endsection
