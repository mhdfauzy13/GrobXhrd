@extends('layouts.app')
@section('title', 'Overtime/create')
@section('content')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xl-6">
                        <h1>Overtime</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create Overtime</h3>
                    </div>

                    <form action="{{ route('overtime.store') }}" method="POST">
                        @csrf
                        <div class="card-body">

                            <div class="form-group">
                                <label for="employee_name">Employee Name</label>
                                <input type="text" id="employee_name" class="form-control"
                                    placeholder="Type to search employee" onkeyup="filterEmployees()" required>
                                <input type="hidden" name="employee_id" id="employee_id" required>
                                <div id="employee-list" class="list-group" style="display: none;"></div>
                            </div>

                            <div class="form-group">
                                <label for="overtime_date">Overtime Date</label>
                                <input type="date" name="overtime_date" id="overtime_date" class="form-control" required>
                            </div>

                            <div class="form-goup">
                                <label for="duration" class="form-label">Duration (in hours)</label>
                                <input type="number" name="duration" id="duration" class="form-control" min="1"
                                    max="8" step="1" required placeholder="Enter duration in whole hours">
                                <div class="invalid-feedback" id="duration-feedback" style="display: none;">
                                    Duration must be a whole number between 1 and 8 hours.
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" required></textarea>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
