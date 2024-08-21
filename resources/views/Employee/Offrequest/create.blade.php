@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create Offrequest</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('offrequest.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="manager">Manager</label>
                        <select name="manager" id="manager" class="form-control" required>
                            @foreach (\Spatie\Permission\Models\Role::all() as $manager)
                                <option value="{{ $manager->name }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mtitle">Title</label>
                        <input type="text" id="mtitle" name="mtitle" class="form-control" required>
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
