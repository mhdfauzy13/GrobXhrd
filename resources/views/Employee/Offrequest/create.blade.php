@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add Off Requests</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('offrequests.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="title">Judul</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="Masukkan judul permohonan cuti" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" class="form-control" id="description" rows="4" placeholder="Masukkan deskripsi permohonan cuti" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="start_event">Tanggal Mulai</label>
                        <input type="date" name="start_event" class="form-control" id="start_event" required>
                    </div>

                    <div class="form-group">
                        <label for="end_event">Tanggal Selesai</label>
                        <input type="date" name="end_event" class="form-control" id="end_event" required>
                    </div>

                    <div class="form-group">
                        <label for="manager_id">Pilih Manager</label>
                        <select name="manager_id" class="form-control" id="manager_id" required>
                            <option value="" disabled selected>Pilih manager...</option>
                            @foreach($managers as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('offrequests.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </section>
@endsection
