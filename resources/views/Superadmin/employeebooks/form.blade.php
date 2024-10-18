@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="container">
            <h2>{{ ucfirst($type) }} Form</h2>
            <form id="employee-book-form" action="{{ route('employeebooks.store') }}" method="POST">
                @csrf <!-- Token CSRF untuk keamanan -->

                <div class="form-group">
                    <label for="employee">Employee</label>
                    <select class="form-control" id="employee" name="employee" required>
                        <option value="">Select Employee</option>
                        <!-- Loop through employees from the database -->
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="incident_date">Incident Date</label>
                    <input type="date" class="form-control" id="incident_date" name="incident_date" required>
                </div>

                <div class="form-group">
                    <label for="incident_details">Incident Details</label>
                    <textarea class="form-control" id="incident_details" name="incident_details" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                </div>

                <!-- Add a hidden input to store the type of the form -->
                <input type="hidden" name="type" value="{{ $type }}">

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </section>
@endsection
