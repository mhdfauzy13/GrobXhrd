@extends('layouts.app')
@section('title', 'Employee History Edit')

@section('content')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Edit Employee History</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit History</h3>
                    </div>

                    <form action="{{ route('history.update', $employee->employee_id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <!-- Employee selection -->
                            <div class="form-group">
                                <label for="employee_id">Employee</label>
                                <select name="employee_id_display" id="employee_id" class="form-control" disabled>
                                    <option value="">-- Select Employee --</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->employee_id }}"
                                            {{ $employee->employee_id == $emp->employee_id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Hidden input to send employee_id value -->
                                <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                            </div>


                            <!-- Resign Date -->
                            <div class="form-group">
                                <label for="resign_date">Resign Date</label>
                                <input type="date" name="resign_date" id="resign_date" class="form-control"
                                    value="{{ old('resign_date', $employee->resign_date) }}" required>
                            </div>

                            <!-- Reason -->
                            <div class="form-group">
                                <label for="reason">Reason</label>
                                <input type="text" name="reason" id="reason" class="form-control"
                                    value="{{ old('reason', $employee->reason) }}" required>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control">{{ old('remarks', $employee->remarks) }}</textarea>
                            </div>

                            <!-- Document -->
                            <div class="form-group">
                                <label for="document">Upload Document</label>
                                <input type="file" name="document" id="document" class="form-control">
                                @if ($employee->document)
                                    <a href="{{ Storage::url($employee->document) }}" target="_blank">Download Existing
                                        Document</a>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a> <!-- Back button -->
                        </div>
                    </form>


                </div>
            </div>
        </section>
    </div>
@endsection
