@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Judul dinamis berdasarkan kategori -->
        <div class="card card-primary w-100">
            <div class="card-header">
                <h3 class="card-title">
                    @if ($employeeBook->category == 'violation')
                        Detail Violation
                    @elseif($employeeBook->category == 'warning')
                        Detail Warning
                    @elseif($employeeBook->category == 'reprimand')
                        Detail Reprimand
                    @else
                        Detail Employee Book
                    @endif
                </h3>
            </div>

            <form>
                <div class="card-body">
                    <div class="form-group">
                        <label for="employee_id">Employee</label>
                        <input type="text" class="form-control"
                            value="{{ $employeeBook->employee->first_name }} {{ $employeeBook->employee->last_name }}"
                            readonly>
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
                </div>
            </form>

            <div class="card-footer">
                <a href="{{ route('employeebooks.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
@endsection
