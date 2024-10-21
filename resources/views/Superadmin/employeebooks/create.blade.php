@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Employee Book</h1>

        <form action="{{ route('employeebooks.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="employee_id">Employee</label>
                <select name="employee_id" class="form-control" required>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->employee_id }}">{{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="incident_date">Incident Date</label>
                <input type="date" name="incident_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="incident_detail">Incident Detail</label>
                <textarea name="incident_detail" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label for="remarks">Remarks</label>
                <textarea name="remarks" class="form-control" required></textarea>
            </div>

            <input type="hidden" name="category" value="{{ request('category') }}">

            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('employeebooks.index') }}" class="btn btn-danger">Cancel</a>
        </form>
    </div>
@endsection
