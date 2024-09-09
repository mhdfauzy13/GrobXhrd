@extends('layouts.app')

@section('head')
    <!-- Load FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css">
    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
        
        /* Tambahkan border pada tabel kalender */
        .fc td, .fc th {
            border: 1px solid #dee2e6 !important;
        }
        
        /* Styling untuk event libur */
        .fc-event.holiday {
            background-color: #ffcccc;
            border-color: #ff0000;
            color: #000;
        }
        
        /* Styling untuk event cuti */
        .fc-event.leave {
            background-color: #cce5ff;
            border-color: #004085;
            color: #000;
        }
        
        /* Custom layout */
        .input-container {
            display: flex;
            justify-content: space-between;
        }
        
        .form-container {
            max-width: 300px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1>Kalender Libur Indonesia</h1>
        
        <div class="input-container">
            <!-- Form untuk input event -->
            <div class="form-container">
                <form id="addEventForm" action="{{ route('holiday.createEvent') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="eventName">Nama Event</label>
                        <input type="text" class="form-control" id="eventName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="eventDate">Tanggal</label>
                        <input type="date" class="form-control" id="eventDate" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="eventTime">Jam</label>
                        <input type="time" class="form-control" id="eventTime" name="time">
                    </div>
                    <div class="form-group">
                        <label for="eventColor">Warna Event</label>
                        <input type="color" class="form-control" id="eventColor" name="color" value="#ff0000">
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Event</button>
                </form>
            </div>
            
            <!-- Kalender -->
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Load jQuery, FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi FullCalendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(info, successCallback, failureCallback) {
                    $.ajax({
                        url: '/superadmin/holiday/data',
                        method: 'GET',
                        success: function(response) {
                            successCallback(response);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            failureCallback(xhr.responseText);
                        }
                    });
                },
                eventClassNames: function(arg) {
                    if (arg.event.extendedProps.type === 'holiday') {
                        return ['holiday'];
                    } else if (arg.event.extendedProps.type === 'leave') {
                        return ['leave'];
                    }
                    return [];
                },
                eventDidMount: function(info) {
                    info.el.setAttribute('title', info.event.extendedProps.description); // Tambahkan tooltip
                },
                eventClick: function(info) {
                    alert('Keterangan: ' + info.event.extendedProps.description); // Menampilkan deskripsi event
                }
            });

            calendar.render();

            // Event handler untuk menambah event baru
            $('#addEventForm').submit(function(e) {
                e.preventDefault();
                
                var eventName = $('#eventName').val();
                var eventDate = $('#eventDate').val();
                var eventTime = $('#eventTime').val();
                var eventColor = $('#eventColor').val();
                
                $.ajax({
                    url: '{{ route('holiday.createEvent') }}',  // Pastikan nama rute sesuai
                    method: 'POST',
                    data: {
                        name: eventName,
                        date: eventDate,
                        time: eventTime,
                        color: eventColor,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        calendar.refetchEvents(); // Refresh kalender
                        alert('Event berhasil ditambahkan');
                    },
                    error: function(xhr) {
                        alert('Gagal menambah event');
                    }
                });
            });

            // Tombol untuk sinkronisasi libur nasional
            $('#syncHolidays').click(function() {
                $.ajax({
                    url: '/holidays/sync',
                    type: 'GET',
                    success: function(response) {
                        alert(response.message);  // Tampilkan pesan sukses
                        calendar.refetchEvents(); // Refresh kalender
                    },
                    error: function() {
                        alert('Gagal sinkronisasi libur');
                    }
                });
            });
        });
    </script>
@endsection
