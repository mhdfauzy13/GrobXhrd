@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recruitment List</h3>
                <div class="card-tools">
                    <a class="btn btn-primary btn-sm" href="{{ route('recruitment.create') }}">
                        <i class="fas fa-plus"></i> Create
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Date of Birth</th>
                                <th>Last Education</th>
                                <th>Last Position</th>
                                <th>CV File</th>
                                <th>Comment</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recruitments as $recruitment)
                                <tr>
                                    <td>{{ $recruitment->name }}</td>
                                    <td>{{ $recruitment->email }}</td>
                                    <td>{{ $recruitment->phone_number }}</td>
                                    <td>{{ $recruitment->date_of_birth }}</td>
                                    <td>{{ $recruitment->last_education }}</td>
                                    <td>{{ $recruitment->last_position }}</td>
                                    <td>
                                        <a href="{{ Storage::url($recruitment->cv_file) }}" target="_blank">Download CV</a>
                                    </td>
                                    <td>{{ $recruitment->comment }}</td>
                                    <td class="project-state">
                                        <span
                                            class="badge badge-{{ $recruitment->status == 'accepted' ? 'success' : 'danger' }}">
                                            {{ $recruitment->status == 'accepted' ? 'Accepted' : 'Rejected' }}
                                        </span>
                                    </td>
                                    <td class="project-actions text-right">
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('recruitment.edit', $recruitment->recruitment_id) }}">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>

                                        <form action="{{ route('recruitment.destroy', $recruitment->recruitment_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this recruitment?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
