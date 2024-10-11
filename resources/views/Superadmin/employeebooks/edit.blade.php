@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Employee Book</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.employeebooks.update', $employeeBook->employeebook_id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Form fields here -->
                    <div class="form-group">
                        <label for="employee_id">Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->employee_id }}"
                                    {{ old('employee_id', $employeeBook->employee_id) == $employee->employee_id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="incident_date">Incident Date</label>
                        <input type="date" class="form-control" id="incident_date" name="incident_date"
                            value="{{ old('incident_date', $employeeBook->incident_date) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="incident_details">Incident Details</label>
                        <textarea class="form-control" id="incident_details" name="incident_details" rows="5" required>{{ old('incident_details', $employeeBook->incident_details) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="5" required>{{ old('remarks', $employeeBook->remarks) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="violation"
                                {{ old('category', $employeeBook->category) === 'violation' ? 'selected' : '' }}>Violation
                            </option>
                            <option value="warning"
                                {{ old('category', $employeeBook->category) === 'warning' ? 'selected' : '' }}>Warning
                            </option>
                            <option value="reprimand"
                                {{ old('category', $employeeBook->category) === 'reprimand' ? 'selected' : '' }}>Reprimand
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
