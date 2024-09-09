@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <style>
        #calendar {
            max-width: 100%;
            height: 80vh;
            margin: 0 auto;
            flex-grow: 1;
        }

        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            height: 100vh;
            padding: 20px;
        }

        #external-events {
            padding: 10px;
            width: 250px;
            background: #f4f4f4;
            border: 1px solid #ccc;
            margin-right: 20px;
        }

        .fc-event {
            margin: 10px 0;
            padding: 5px;
            cursor: pointer;
        }

        .form-container {
            margin-top: 20px;
        }

        .color-options {
            display: flex;
            margin-bottom: 10px;
        }

        .color-options div {
            width: 30px;
            height: 30px;
            border: 1px solid #ccc;
            margin-right: 10px;
            cursor: pointer;
        }

        .btn-add-event {
            margin-top: 10px;
        }

        .event-title-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .event-title-container input {
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Draggable Events -->
        <div id="external-events">
            <p><strong>Draggable Events</strong></p>
            <!-- Bagian draggable events kosong, akan diisi secara dinamis -->
            <p>
                <input type="checkbox" id="drop-remove">
                <label for="drop-remove">remove after drop</label>
            </p>

            <!-- Formulir Tambah Event -->
            <div class="form-container">
                <h3>Tambah Event</h3>
                <div class="color-options">
                    <div style="background-color: blue;" data-color="blue"></div>
                    <div style="background-color: yellow;" data-color="yellow"></div>
                    <div style="background-color: green;" data-color="green"></div>
                    <div style="background-color: red;" data-color="red"></div>
                    <div style="background-color: gray;" data-color="gray"></div>
                </div>
                <div class="event-title-container">
                    <input type="text" class="form-control" id="eventTitle" placeholder="Title Event">
                    <button type="submit" id="addEventButton" class="btn btn-primary btn-add-event">Add</button>
                </div>
                <input type="hidden" id="eventColor" name="color" value="#ff0000">
            </div>
        </div>

        <!-- Kalender -->
        <div id="calendar"></div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.4/main.min.js'></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi FullCalendar Draggable
            var draggable = new FullCalendar.Draggable(document.getElementById('external-events'), {
                itemSelector: '.fc-event',
                eventData: function(eventEl) {
                    return {
                        title: eventEl.innerText,
                        backgroundColor: eventEl.style.backgroundColor
                    };
                }
            });

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                editable: true,
                droppable: true,
                drop: function(info) {
                    // Mengecek jika opsi 'remove after drop' dicentang
                    if (document.getElementById('drop-remove').checked) {
                        info.draggedEl.parentNode.removeChild(info.draggedEl); // Menghapus event dari draggable list
                    }
                    // Event tetap muncul di daftar, tetapi tidak muncul dua kali saat di-drag
                },
                events: function(info, successCallback, failureCallback) {
                    $.ajax({
                        url: '{{ route('holiday.calendar.data') }}',
                        method: 'GET',
                        success: function(response) {
                            successCallback(response);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            failureCallback(xhr.responseText);
                        }
                    });
                }
            });

            calendar.render();

            // Pilihan warna untuk event
            $('.color-options div').click(function() {
                var color = $(this).data('color');
                $('#eventColor').val(color);
                $('#addEventButton').css('background-color', color);
                $('#addEventButton').css('color', getTextColor(color)); // Set warna teks tombol sesuai dengan latar belakang
            });

            function getTextColor(bgColor) {
                // Fungsi untuk menentukan warna teks berdasarkan warna latar belakang
                var color = bgColor.replace('#', '');
                var r = parseInt(color.substr(0, 2), 16);
                var g = parseInt(color.substr(2, 2), 16);
                var b = parseInt(color.substr(4, 2), 16);

                // Menggunakan rumus kontras untuk menentukan apakah warna teks harus putih atau hitam
                var brightness = (r * 299 + g * 587 + b * 114) / 1000;
                return (brightness > 128) ? 'black' : 'white';
            }

            // Tambah event ke draggable events
            $('#addEventButton').click(function() {
                var eventTitle = $('#eventTitle').val();
                var eventColor = $('#eventColor').val();
                
                if (eventTitle.trim() === '') {
                    alert('Please enter an event title.');
                    return;
                }
                
                // Tambahkan event ke draggable events list
                var newEvent = $('<div class="fc-event"></div>').text(eventTitle).css({
                    'background-color': eventColor,
                    'color': getTextColor(eventColor),
                    'margin': '10px 0',
                    'cursor': 'pointer'
                });

                $('#external-events').append(newEvent);

                // Update draggable pada elemen yang baru ditambahkan
                new FullCalendar.Draggable(newEvent[0], {
                    itemSelector: '.fc-event',
                    eventData: function(eventEl) {
                        return {
                            title: eventEl.innerText,
                            backgroundColor: eventEl.style.backgroundColor
                        };
                    }
                });

                // Reset form setelah klik
                $('#eventTitle').val('');
                $('#eventColor').val('#ff0000');
                $('#addEventButton').css('background-color', ''); // Reset warna tombol
                $('#addEventButton').css('color', ''); // Reset warna teks tombol
            });
        });
    </script>
@endsection
