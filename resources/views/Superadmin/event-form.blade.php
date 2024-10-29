<x-modal-action action="{{ $action }}">
    @if ($data->event_id)
        @method('PUT')
    @endif
    <div class="row">
        <div class="col-6">
            <div class="mb-3">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date"
                    value="{{ $data->start_date ?? request()->start_date }}" class="form-control" required>
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date"
                    value="{{ $data->end_date ?? request()->end_date }}" class="form-control" required>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <textarea name="title" class="form-control" required>{{ $data->title }}</textarea>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" {{ $data->category == 'success' ? 'checked' : null }} type="radio"
                        name="category" id="category-success" value="success" required>
                    <label class="form-check-label" for="category-success">Success</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" {{ $data->category == 'danger' ? 'checked' : null }} type="radio"
                        name="category" id="category-danger" value="danger" required>
                    <label class="form-check-label" for="category-danger">Danger</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" {{ $data->category == 'warning' ? 'checked' : null }} type="radio"
                        name="category" id="category-warning" value="warning" required>
                    <label class="form-check-label" for="category-warning">Warning</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" {{ $data->category == 'info' ? 'checked' : null }} type="radio"
                        name="category" id="category-info" value="info" required>
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

<!-- Include Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
