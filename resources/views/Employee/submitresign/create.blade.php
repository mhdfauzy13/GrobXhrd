@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xl-6">
                        <h1>Submit Resignation</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Submit Your Resignation Request</h3>
                    </div>

                    <form method="POST" action="{{ route('submitresign.store') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="employee_id">Employee</label>
                                @if ($employees->isEmpty())
                                    <p class="text-danger">No employees available. Please contact the admin.</p>
                                @else
                                    <select name="employee_id" id="employee_id" class="form-control" required>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->employee_id }}">
                                                {{ $employee->first_name }} {{ $employee->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="resign_date">Resign Date</label>
                                <input type="date" name="resign_date" id="resign_date" class="form-control"
                                    min="{{ now()->toDateString() }}" required>
                            </div>

                            <div class="form-group">
                                <label for="reason">Reason</label>
                                <textarea name="reason" id="reason" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control"></textarea>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('submitresign.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
