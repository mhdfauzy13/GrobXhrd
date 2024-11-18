@extends('layouts.app')

@section('content')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xl-6">
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Recruitment</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('recruitment.update', $recruitment->recruitment_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

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
                                <select name="last_education" id="last_education" class="form-control" required>
                                    <option value="">-- Select Education Level --</option>
                                    <option value="Elementary School"
                                        {{ old('last_education', $recruitment->last_education) == 'Elementary School' ? 'selected' : '' }}>
                                        Elementary School</option>
                                    <option value="Junior High School"
                                        {{ old('last_education', $recruitment->last_education) == 'Junior High School' ? 'selected' : '' }}>
                                        Junior High School</option>
                                    <option value="Senior High School"
                                        {{ old('last_education', $recruitment->last_education) == 'Senior High School' ? 'selected' : '' }}>
                                        Senior High School</option>
                                    <option value="Vocational High School"
                                        {{ old('last_education', $recruitment->last_education) == 'Vocational High School' ? 'selected' : '' }}>
                                        Vocational High School</option>
                                    <option value="Associate Degree 1"
                                        {{ old('last_education', $recruitment->last_education) == 'Associate Degree 1' ? 'selected' : '' }}>
                                        Associate Degree 1</option>
                                    <option value="Associate Degree 2"
                                        {{ old('last_education', $recruitment->last_education) == 'Associate Degree 2' ? 'selected' : '' }}>
                                        Associate Degree 2</option>
                                    <option value="Associate Degree 3"
                                        {{ old('last_education', $recruitment->last_education) == 'Associate Degree 3' ? 'selected' : '' }}>
                                        Associate Degree 3</option>
                                    <option value="Bachelor’s Degree"
                                        {{ old('last_education', $recruitment->last_education) == 'Bachelor’s Degree' ? 'selected' : '' }}>
                                        Bachelor’s Degree</option>
                                    <option value="Master’s Degree"
                                        {{ old('last_education', $recruitment->last_education) == 'Master’s Degree' ? 'selected' : '' }}>
                                        Master’s Degree</option>
                                    <option value="Doctoral Degree"
                                        {{ old('last_education', $recruitment->last_education) == 'Doctoral Degree' ? 'selected' : '' }}>
                                        Doctoral Degree</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="last_position">Last Position</label>
                                <input type="text" class="form-control" id="last_position" name="last_position"
                                    value="{{ old('last_position', $recruitment->last_position) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="apply_position">Apply Position</label>
                                <input type="text" name="apply_position" id="apply_position" class="form-control"
                                    value="{{ old('apply_position', $recruitment->apply_position) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="cv_file">CV File</label>
                                <input type="file" name="cv_file" id="cv_file" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="comment">Comment</label>
                                <textarea name="comment" id="comment" class="form-control">{{ old('comment', $recruitment->comment) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="Initial Interview"
                                        {{ old('status', $recruitment->status) == 'Initial Interview' ? 'selected' : '' }}>
                                        Initial Interview</option>
                                    <option value="User Interview 1"
                                        {{ old('status', $recruitment->status) == 'User Interview 1' ? 'selected' : '' }}>
                                        User
                                        Interview 1</option>
                                    <option value="User Interview 2"
                                        {{ old('status', $recruitment->status) == 'User Interview 2' ? 'selected' : '' }}>
                                        User
                                        Interview 2</option>
                                    <option value="Background Check"
                                        {{ old('status', $recruitment->status) == 'Background Check' ? 'selected' : '' }}>
                                        Background Check</option>
                                    <option value="Offering letter"
                                        {{ old('status', $recruitment->status) == 'Offering letter' ? 'selected' : '' }}>
                                        Offering letter</option>
                                    <option value="Accept"
                                        {{ old('status', $recruitment->status) == 'Accept' ? 'selected' : '' }}>Accept
                                    </option>
                                    <option value="Decline"
                                        {{ old('status', $recruitment->status) == 'Decline' ? 'selected' : '' }}>Decline
                                    </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endsection
