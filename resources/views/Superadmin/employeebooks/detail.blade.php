@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detail Employee Book</h1>
        <h3>Employee: {{ $employeeBook->employee->first_name }} {{ $employeeBook->employee->last_name }}</h3>
        <p><strong>Incident Date:</strong> {{ $employeeBook->incident_date }}</p>
        <p><strong>Incident Detail:</strong> {{ $employeeBook->incident_detail }}</p>
        <p><strong>Remarks:</strong> {{ $employeeBook->remarks }}</p>
        <p><strong>Category:</strong> {{ $employeeBook->category }}</p>
        <a href="{{ route('employeebooks.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection
