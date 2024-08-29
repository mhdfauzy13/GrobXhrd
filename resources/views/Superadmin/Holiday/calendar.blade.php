@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Kalender Libur Indonesia</h1>
        <div id="calendar" style="overflow-x: auto;"></div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.7/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.7/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.7/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.7/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.7/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/scrollgrid@6.1.7/main.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                editable: false,
                selectable: true,
                events: '/holidays/data',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                scrollTime: '00:00',
                views: {
                    dayGridMonth: {
                        titleFormat: {
                            year: 'numeric',
                            month: 'long'
                        }
                    }
                }
            });
            calendar.render();
        });
    </script>
@endsection