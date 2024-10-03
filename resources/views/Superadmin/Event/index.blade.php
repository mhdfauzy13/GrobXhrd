@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>

    <div class="container">
        <div class="row">
            <div class="col-12 mt-3">
                <div id='calendar'></div>
            </div>
        </div>
    </div>

    <div id="modal-action" class="modal" tabindex="-1">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.15/index.global.min.js'></script>

    <script>
        const modal = $('#modal-action')

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                events: '{{ route('events.list') }}',
                editable: true,
                dateClick: function(info) {
                    console.log(info);

                    $.ajax({
                        url: '{{ route('events.create') }}',
                        data: {
                            start_date: info.dateStr,
                            end_date: info.dateStr,
                        },
                        success: function(res) {
                            modal.html(res).modal('show')

                            $('#form-action').on('submit', function(e) {
                                e.preventDefault()
                                const form = this
                                const formData = new FormData(form)
                                $.ajax({
                                    url: form.action,
                                    method: form.method,
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(res) {
                                        modal.modal('hide')
                                        calendar.refetchEvents()
                                    }

                                })

                            })
                        }
                    })
                },
                eventClick: function({
                    event
                }) {
                    $.ajax({
                        url: '{{ url('events') }}/' + event.id + '/edit',
                        success: function(res) {
                            modal.html(res).modal('show')

                            $('#form-action').on('submit', function(e) {
                                e.preventDefault()
                                const form = this
                                const formData = new FormData(form)
                                $.ajax({
                                    url: form.action,
                                    method: form.method,
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(res) {
                                        modal.modal('hide')
                                        calendar.refetchEvents()
                                    }

                                })

                            })
                        }
                    })
                }
            });
            calendar.render();
        });
    </script>
@endsection
