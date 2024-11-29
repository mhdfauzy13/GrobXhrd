@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Submit Resignation Request</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('resignationrequest.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Name Input (ambil nama dari Auth) -->
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" readonly>
                    </div>

                    <!-- Resign Date -->
                    <div class="form-group mb-3">
                        <label for="resign_date" class="form-label">Resign Date</label>
                        <input type="date" class="form-control" name="resign_date" min="{{ now()->toDateString() }}"
                            required>
                    </div>

                    <!-- Reason for Resignation -->
                    <div class="form-group mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea class="form-control" name="reason" rows="3" required></textarea>
                    </div>

                    <!-- Remarks (optional) -->
                    <div class="form-group mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" rows="3"></textarea>
                    </div>

                    <!-- Manager Selection Dropdown -->
                    <div class="form-group mb-3">
                        <label for="manager_id" class="form-label">Select Manager</label>
                        @if ($managers->isNotEmpty())
                            <select class="form-select" name="manager_id" required>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->user_id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <p class="text-danger">No managers available. Please contact the admin.</p>
                        @endif
                    </div>

                    <!-- Document Upload -->
                    <div class="form-group mb-3">
                        <label for="document" class="form-label">Document</label>
                        <input type="file" class="form-control" name="document" accept=".pdf,.doc,.docx">
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
