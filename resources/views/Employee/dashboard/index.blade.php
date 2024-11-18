@extends('layouts.app')

@section('content')
    <h1>HALOO INI DASHBOARD EMPLOYEE</h1>

    <div class="container">
        <div class="row">
            <!-- Create two columns: one for the calendar and one for other content -->
            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <!-- Calendar Container styled like AdminLTE's card -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Event Calendar</h3>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <!-- Another column for other content can be added here -->
            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                <!-- Other content for the dashboard can go here -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Other Dashboard Info</h3>
                    </div>
                    <div class="card-body">
                        <!-- Content goes here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.15/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5', // Ensure Bootstrap 5 theme is used
                events: '{{ route('employee.events.list') }}', // Fetch events
                editable: false, // Disable event editing
                droppable: false, // Disable event dragging
                contentHeight: 'auto', // Make the calendar height dynamic
                height: 'auto', // Adjust height based on the content
                aspectRatio: 1.5, // Adjust the aspect ratio if necessary (controls width/height ratio)
            });
            calendar.render();
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
