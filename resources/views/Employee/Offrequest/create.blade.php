@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create Offrequest</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('offrequests.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                    <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                    <div class="form-group">
                        <label for="manager_id">Manager</label>
                        <select name="manager_id" id="manager_id" class="form-control" required>
                            <option value="">Pilih Manajer</option>
                            @foreach ($managers as $user_id => $name)
                                <option value="{{ $user_id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="start_event">Start Event</label>
                        <input type="datetime-local" id="start_event" name="start_event" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="end_event">End Event</label>
                        <input type="datetime-local" id="end_event" name="end_event" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </section>
@endsection
