@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-4">
                    <form action="{{ route('payroll.index') }}" method="GET">
                        <div class="input-group">
                            <select name="month" class="form-control">
                                <option value="">Select Month</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                            <select name="year" class="form-control">
                                <option value="">Select Year</option>
                                @foreach (range(date('Y') - 5, date('Y')) as $yearOption)
                                    <option value="{{ $yearOption }}" {{ $year == $yearOption ? 'selected' : '' }}>
                                        {{ $yearOption }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payroll</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th style="width: 25%">Employee Name</th>
                                    <th style="width: 15%" class="text-center">Total Days Worked</th>
                                    <th style="width: 15%" class="text-center">Total Days Off</th>
                                    <th style="width: 15%" class="text-center">Total Late Check In</th>
                                    <th style="width: 15%" class="text-center">Total Early Check Out</th>
                                    <th style="width: 15%" class="text-center">Effective Work Days</th>
                                    <th style="width: 15%" class="text-center">Current Salary</th>
                                    <th style="width: 15%" class="text-center">Total Salary</th>
                                    <th style="width: 15%" class="text-center">Validation Status</th>
                                    <th style="width: 15%" class="text-right">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($payrolls as $payroll)
                                    <tr>  
                                        <td>{{ $payroll->employee->name }}</td>
                                        <td class="text-center">{{ $payroll->days_present }}</td>
                                        <td class="text-center">{{ $payroll->total_leave }}</td>
                                        <td class="text-center">{{ $payroll->total_late }}</td>
                                        <td class="text-center">{{ $payroll->total_early }}</td>
                                        <td class="text-center">{{ $payroll->effective_work_days }}</td>
                                        <td class="text-center">Rp {{ number_format($payroll->current_salary, 0, ',', '.') }}</td>
                                        <td class="text-center">Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <form method="POST" action="{{ route('payroll.validate', $payroll->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <select name="validation_status" class="form-select" onchange="this.form.submit()">
                                                    <option value="not_validated" {{ $payroll->validation_status == 'not_validated' ? 'selected' : '' }}>Not Validated</option>
                                                    <option value="validated" {{ $payroll->validation_status == 'validated' ? 'selected' : '' }}>Validated</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="text-right">
                                            {{-- Additional action buttons can be added here --}}
                                        </td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
