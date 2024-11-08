@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-4">
                    <form action="{{ route('payroll.index') }}" method="GET">
                        <div class="input-group">
                            <label for="month">Month:</label>
                            <select name="month" id="month">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>

                            <label for="year">Year:</label>
                            <select name="year" id="year">
                                @foreach (range(date('Y') - 5, date('Y')) as $y)
                                    <!-- Menampilkan tahun dari 5 tahun lalu hingga sekarang -->
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
                
            <form method="GET" action="{{ route('payroll.export') }}">
                <button type="submit" class="btn btn-primary">Export to CSV</button>
            </form>
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
                                    <th style="width: 20%">Employee Name</th>
                                    <th style="width: 10%" class="text-center">Total Days Worked</th>
                                    <th style="width: 10%" class="text-center">Total Days Off</th>
                                    <th style="width: 10%" class="text-center">Total Late Check In</th>
                                    <th style="width: 10%" class="text-center">Total Early Check Out</th>
                                    <th style="width: 10%" class="text-center">Effective Work Days</th>
                                    <th style="width: 10%" class="text-center">Current Salary</th>
                                    <th style="width: 10%" class="text-center">Overtime Pay</th>
                                    <th style="width: 10%" class="text-center">Total Salary</th>
                                    <th style="width: 10%" class="text-center">Validation Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payrolls as $payroll)
                                    <tr>
                                        <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                                        <td>{{ $payroll->attandanceRecap->total_present }}</td>
                                        <td>{{ $payroll->attandanceRecap->total_absent }}</td>
                                        <td>{{ $payroll->attandanceRecap->total_late }}</td>
                                        <td>{{ $payroll->attandanceRecap->total_early }}</td>
                                        <td>{{ $payroll->workdaySetting->monthly_workdays }}</td>
                                        <td>{{ $payroll->employee->current_salary }}</td>
                                        <td>{{ $payroll->overtime_pay }}</td>
                                        <td>{{ $payroll->total_salary }}</td>
                                        <td>
                                            @if ($payroll->validation_status)
                                                <button disabled>Valid</button>
                                            @else
                                                <button onclick="validatePayroll({{ $payroll->id }})">Validate</button>
                                            @endif
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
