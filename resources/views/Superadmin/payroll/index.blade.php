@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-4">
                    <form action="{{ route('payroll.index') }}" method="GET">
  
             
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payroll</h3>
                
                    <div class="card-tools">
                        <form method="GET" action="{{ route('payroll.export') }}">
                            <button type="submit" class="btn btn-primary">Export to CSV</button>
                        </form>
                      
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th style="width: 20%">Employee Name</th>
                                    <th style="width: 10%" class="text-center">Current Salary</th>
                                    <th style="width: 10%" class="text-center">Total Days Worked</th>
                                    <th style="width: 10%" class="text-center">Total Days Off</th>
                                    <th style="width: 10%" class="text-center">Total Late Check In</th>
                                    <th style="width: 10%" class="text-center">Total Early Check Out</th>
                                    <th style="width: 10%" class="text-center">Effective Work Days</th>
                                    <th style="width: 10%" class="text-center">Overtime Pay</th>
                                    <th style="width: 10%" class="text-center">Total Salary</th>
                                    <th style="width: 10%" class="text-center">Validation Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                  @foreach ($payrollData as $data)
                                <tr>
                                    <td>{{ $data['employee_name'] }}</td>
                                    <td>{{ number_format($data['current_salary'], 0, ',', '.') }}</td>
                                    <td>{{ $data['total_days_worked'] }}</td>
                                    <td>{{ $data['total_days_off'] }}</td>
                                    <td>{{ $data['total_late_check_in'] }}</td>
                                    <td>{{ $data['total_early_check_out'] }}</td>
                                    <td>{{ $data['monthly_workdays'] }}</td>
                                    {{-- <td>{{ $data['overtime_pay'] }}</td> --}}
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
