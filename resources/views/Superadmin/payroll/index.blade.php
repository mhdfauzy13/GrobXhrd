@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        {{-- <div class="container-fluid"> --}}

        <!-- Default box -->
        <div class="card">
            <div class="card-header">

                <div class="d-flex justify-content-between w-100 align-items-center">
                    <!-- Title and Search Form -->
                    <h3 class="card-title mb-0">Payroll</h3>
                    <div class="d-flex align-items-center">

                        <form method="GET" action="{{ route('payroll.index') }}" class="form-inline d-flex mb-0">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by employee name..." value="{{ request()->query('search') }}">
                            <button type="submit" class="btn btn-secondary ml-2">Search</button>
                        </form>

                        <!-- Export Button with margin -->
                        {{-- <form method="GET" action="{{ route('payroll.exports') }}" class="ml-3">
                            <button type="submit" class="btn btn-primary">Export to CSV</button>
                        </form> --}}

                        <form method="GET" action="{{ route('payroll.exports') }}" class="ml-3">
                            <button type="submit" class="btn btn-primary">Export to CSV</button>
                        </form>
                        

{{-- 
                        <form method="GET" action="{{ route('payroll.export') }}" class="ml-3">
                            <button type="submit" class="btn btn-primary" {{ $payrollData->where('validation_status', 'approved')->count() == 0 ? 'disabled' : '' }}>Export to CSV</button>
                        </form> --}}

                </div>
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
                                    @if ($data['status'] == 'pending')
                                        <form action="{{ route('payroll.updateStatus', $data['id']) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="status" value="approved" class="btn btn-success btn-sm">Approve</button>
                                            {{-- <button type="submit" name="status" value="rejected" class="btn btn-danger btn-sm" onclick="return confirmDecline()">Decline</button> --}}
                                        </form>
                                    @elseif ($data['status'] == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif ($data['status'] == 'rejected')
                                        <span class="badge bg-danger">Declined</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer clearfix">
            <div class="pagination-container">
                {{-- {{ $payrollData('vendor.pagination.bootstrap-4') }} --}}
            </div>
        </div>
        </div>
        {{-- </div> --}}
    </section>





    <script>
        // JavaScript untuk memunculkan konfirmasi sebelum decline
        function confirmDecline() {
            return confirm("Are you sure you want to decline this payroll data? Please verify the data first.");
        }
    </script>

@endsection
