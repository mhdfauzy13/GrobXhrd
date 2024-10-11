@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Employee Book List</h3>
                <div class="card-tools">
                    <a class="btn btn-primary btn-sm" href="{{ route('superadmin.employeebooks.create') }}">
                        <i class="fas fa-plus"></i> Create
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Incident Date</th>
                                <th>Incident Details</th>
                                <th>Remarks</th>
                                <th>Category</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employeeBooks as $employeeBook)
                                <tr>
                                    <td>{{ $employeeBook->employee->name }}</td>
                                    <td>{{ $employeeBook->incident_date }}</td>
                                    <td>{{ $employeeBook->incident_details }}</td>
                                    <td>{{ $employeeBook->remarks }}</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $employeeBook->category == 'violation' ? 'danger' : ($employeeBook->category == 'warning' ? 'warning' : 'info') }}">
                                            {{ ucfirst($employeeBook->category) }}
                                        </span>
                                    </td>
                                    <td class="project-actions text-right">
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('superadmin.employeebooks.edit', $employeeBook->employeebook_id) }}">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>

                                        <form
                                            action="{{ route('superadmin.employeebooks.destroy', $employeeBook->employeebook_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this record?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Incident Date Form Group -->
                                <tr>
                                    <td colspan="6">
                                        <div class="form-group">
                                            <label for="incident_date">Incident Date</label>
                                            <input type="date" class="form-control" id="incident_date"
                                                name="incident_date"
                                                value="{{ old('incident_date', $employeeBook->incident_date) }}" required>
                                        </div>
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
