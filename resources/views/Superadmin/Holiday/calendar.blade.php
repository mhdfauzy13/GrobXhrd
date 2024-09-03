@extends('layouts.app')

@section('head')
    <!-- Load FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css">
    <style>
        /* Custom styles for the calendar */
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Tambahkan border pada tabel kalender */
        .fc td, .fc th {
            border: 1px solid #dee2e6 !important;
        }

        /* Styling for holiday events */
        .fc-event.holiday {
            background-color: #ffcccc;
            border-color: #ff0000;
            color: #000;
        }

        /* Styling for leave events */
        .fc-event.leave {
            background-color: #cce5ff;
            border-color: #004085;
            color: #000;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1>Kalender Libur Indonesia</h1>
        <div id="calendar"></div>
    </div>
@endsection

@section('scripts')
    <!-- Load jQuery, Moment.js, and FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: '/holidays/data',
                eventRender: function(event, element) {
                    // Tambahkan class berdasarkan tipe event
                    if (event.type === 'holiday') {
                        element.addClass('holiday');
                    } else if (event.type === 'leave') {
                        element.addClass('leave');
                    }

                    // Tambahkan tooltip dengan deskripsi event
                    element.attr('title', event.description);
                },
                eventClick: function(event) {
                    alert('Keterangan: ' + event.description);
                }
            });
        });
    </script>
@endsection
