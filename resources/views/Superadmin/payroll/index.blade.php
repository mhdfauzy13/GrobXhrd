@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            {{-- <div class="row mb-3">
                <div class="col-md-4">
                    <form action="{{ route('payroll.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search employee name">
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div> --}}
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
                                    <th style="width: 13%" class="text-center">Current Salary</th>
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
                                        <td class="text-center">Rp. {{ number_format($data['current_salary'], 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $data['total_days_worked'] }}</td>
                                        <td class="text-center">{{ $data['total_days_off'] }}</td>
                                        <td class="text-center">{{ $data['total_late_check_in'] }}</td>
                                        <td class="text-center">{{ $data['total_early_check_out'] }}</td>
                                        <td class="text-center">{{ $data['monthly_workdays'] }}</td>
                                        <td class="text-center">Rp. {{ number_format($data['overtime_pay'], 0, ',', '.') }}</td>
                                        <td class="text-center">{{ number_format($data['total_payroll'], 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            {{-- @if ($data['validation_status'] == 'pending') --}}
                                                <form action="
                                                {{-- {{ route('payroll.updateStatus', $data['id']) }} --}}
                                                " method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" name="status" value="approved" class="btn btn-success btn-sm">Accept</button>
                                                    <button type="submit" name="status" value="declined" class="btn btn-danger btn-sm">Decline</button>
                                                </form>
                                            {{-- @elseif ($data['validation_status'] == 'approved')
                                                <span class="badge bg-success">Accepted</span>
                                            @elseif ($data['validation_status'] == 'declined')
                                                <span class="badge bg-danger">Declined</span>
                                            @endif --}}
                                        </td>                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="pagination-container">
                        {{-- {{ $payrollData->links('vendor.pagination.bootstrap-4') }} --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
