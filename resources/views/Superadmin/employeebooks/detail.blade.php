@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detail Employee Book</h1>

        <form>
            <div class="form-group">
                <label for="employee_id">Employee</label>
                <input type="text" class="form-control"
                    value="{{ $employeeBook->employee->first_name }} {{ $employeeBook->employee->last_name }}" readonly>
            </div>

            <div class="form-group">
                <label for="incident_date">Incident Date</label>
                <input type="date" class="form-control" value="{{ $employeeBook->incident_date }}" readonly>
            </div>

            <div class="form-group">
                <label for="incident_detail">Incident Detail</label>
                <textarea class="form-control" readonly>{{ $employeeBook->incident_detail }}</textarea>
            </div>

            <div class="form-group">
                <label for="remarks">Remarks</label>
                <textarea class="form-control" readonly>{{ $employeeBook->remarks }}</textarea>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" class="form-control" value="{{ $employeeBook->category }}" readonly>
            </div>
        </form>

        <a href="{{ route('employeebooks.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection
