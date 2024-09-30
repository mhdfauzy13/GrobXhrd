<x-modal-action action="{{ $action }}">
    @if ($data->events_id)
        @method('put')
    @endif

    <div class="row">
        <div class="col-6">
            <div class="mb-3">
                <input type="text" name="start_date" class="form-control"
                    value="{{ old('start_date', $data->start_date ?? '') }}">
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <input type="text" name="end_date" class="form-control">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <textarea name="title" class="form-control">{{ old('title', $data->title ?? '') }}</textarea>
            </div>
        </div>
    </div>
</x-modal-action>
