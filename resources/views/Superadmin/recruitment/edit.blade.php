@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Recruitment</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('recruitment.update', $recruitment->recruitment_id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Form fields here -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $recruitment->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email', $recruitment->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number"
                            value="{{ old('phone_number', $recruitment->phone_number) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                            value="{{ old('date_of_birth', $recruitment->date_of_birth) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="last_education">Last Education</label>
                        <input type="text" class="form-control" id="last_education" name="last_education"
                            value="{{ old('last_education', $recruitment->last_education) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="last_position">Last Position</label>
                        <input type="text" class="form-control" id="last_position" name="last_position"
                            value="{{ old('last_position', $recruitment->last_position) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="cv_file">CV File</label>
                        <input type="file" class="form-control" id="cv_file" name="cv_file">
                        @if ($recruitment->cv_file)
                            <a href="{{ Storage::url($recruitment->cv_file) }}" target="_blank">Current CV</a>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea class="form-control" id="comment" name="comment">{{ old('comment', $recruitment->comment) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="accepted"
                                {{ old('status', $recruitment->status) === 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected"
                                {{ old('status', $recruitment->status) === 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
