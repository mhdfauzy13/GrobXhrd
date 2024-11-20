@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recruitment Detail</h3>
                <div class="card-tools">
                    <a class="btn btn-secondary btn-sm" href="{{ route('recruitment.index') }}">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td>{{ $recruitment->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $recruitment->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>{{ $recruitment->phone_number }}</td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <td>{{ $recruitment->date_of_birth }}</td>
                    </tr>
                    <tr>
                        <th>Last Education</th>
                        <td>{{ $recruitment->last_education }}</td>
                    </tr>
                    <tr>
                        <th>Last Position</th>
                        <td>{{ $recruitment->last_position }}</td>
                    </tr>
                    <tr>
                        <th>Apply Position</th>
                        <td>{{ $recruitment->apply_position }}</td>
                    </tr>
                    <tr>
                        <th>CV File</th>
                        <td>
                            <a href="{{ Storage::url($recruitment->cv_file) }}" target="_blank">Download CV</a>
                        </td>
                    </tr>
                    <tr>
                        <th>Comment</th>
                        <td>{{ $recruitment->comment }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $recruitment->status }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
@endsection
