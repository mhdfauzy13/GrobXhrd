@extends('layouts.app')
@section('title', 'EmployeeHistory/Create')

@section('content')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xl-6">
                        <h1>Create History</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add History for Employee</h3>
                    </div>

                    <form action="{{ route('history.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <!-- Employee selection -->
                            <div class="form-group">
                                <label for="employee_id">Employee</label>
                                <select name="employee_id" id="employee_id" class="form-control" required>
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->employee_id }}">{{ $employee->first_name }}
                                            {{ $employee->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Resign Date -->
                            <div class="form-group">
                                <label for="resign_date">Resign Date</label>
                                <input type="date" name="resign_date" id="resign_date" class="form-control" required>
                            </div>

                            <!-- Reason -->
                            <div class="form-group">
                                <label for="reason">Reason</label>
                                <input type="text" name="reason" id="reason" class="form-control" required>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control"></textarea>
                            </div>

                            <!-- Document -->
                            <div class="form-group">
                                <label for="document">Upload Document</label>
                                <input type="file" name="document" id="document" class="form-control">
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a> <!-- Back button -->
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
