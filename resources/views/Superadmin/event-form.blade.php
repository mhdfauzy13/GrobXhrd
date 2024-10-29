<x-modal-action action="{{ $action }}">
    @if ($data->event_id)
        @method('PUT')
    @endif
    <div class="row">
        <div class="col-6">
            <div class="mb-3">
                <input type="text" name="start_date" value="{{ $data->start_date ?? request()->start_date }}"
                    class="form-control datepicker">
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <input type="text" name="end_date" value="{{ $data->end_date ?? request()->end_date }}"
                    class="form-control datepicker">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <input type="text" id="event-date" name="event_date" placeholder="Pilih tanggal"
                    class="form-control datepicker" />
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <textarea name="title" class="form-control">{{ $data->title }}</textarea>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" {{ $data->category == 'success' ? 'checked' : null }} type="radio"
                        name="category" id="category-success" value="success">
                    <label class="form-check-label" for="category-success">Success</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" {{ $data->category == 'danger' ? 'checked' : null }} type="radio"
                        name="category" id="category-danger" value="danger">
                    <label class="form-check-label" for="category-danger">Danger</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" {{ $data->category == 'warning' ? 'checked' : null }} type="radio"
                        name="category" id="category-warning" value="warning">
                    <label class="form-check-label" for="category-warning">Warning</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" {{ $data->category == 'info' ? 'checked' : null }} type="radio"
                        name="category" id="category-info" value="info">
                    <label class="form-check-label" for="category-info">Info</label>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="delete" role="switch"
                        id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Delete</label>
                </div>
            </div>
        </div>
    </div>
</x-modal-action>

<!-- Include Bootstrap and Flatpickr JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi modal
        var eventModal = new bootstrap.Modal(document.getElementById('eventModal'), {
            backdrop: 'static', // Menghindari modal ditutup saat klik di luar modal
            keyboard: true
        });

        // Inisialisasi Flatpickr untuk pemilihan tanggal
        flatpickr("#event-date", {
            dateFormat: "Y-m-d",
        });
    });
</script>
