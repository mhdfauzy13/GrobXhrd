@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Employee Book</h1>

        <form action="{{ route('employeebooks.update', $employeeBook->employeebook_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="employee_id">Employee</label>
                <select name="employee_id" class="form-control" required>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->employee_id }}"
                            {{ $employeeBook->employee_id == $employee->employee_id ? 'selected' : '' }}>
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="incident_date">Incident Date</label>
                <input type="date" name="incident_date" class="form-control" value="{{ $employeeBook->incident_date }}"
                    required>
            </div>

            <div class="form-group">
                <label for="incident_detail">Incident Detail</label>
                <textarea name="incident_detail" class="form-control" required>{{ $employeeBook->incident_detail }}</textarea>
            </div>

            <div class="form-group">
                <label for="remarks">Remarks</label>
                <textarea name="remarks" class="form-control" required>{{ $employeeBook->remarks }}</textarea>
            </div>

            <input type="hidden" name="category" value="{{ $employeeBook->category }}">

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
