@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Employee Book</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('superadmin.employeebooks.update', $employeeBook->employeebook_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="category" value="{{ $employeeBook->category }}">

                    <div id="category-info" class="alert alert-info mt-3">
                        Anda sedang mengisi form untuk kategori {{ ucfirst($employeeBook->category) }}.
                    </div>

                    <div id="form-fields">
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
                            <textarea class="form-control" id="remarks" name="remarks" rows="5">{{ old('remarks', $employeeBook->remarks) }}</textarea>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
