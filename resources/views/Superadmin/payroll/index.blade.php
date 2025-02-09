@extends('layouts.app')
@section('title', 'Payroll/index')
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <h3 class="card-title mb-0">Payroll</h3>
                    <div class="d-flex align-items-center">
                        <form method="GET" action="{{ route('payroll.index') }}" class="form-inline d-flex mb-0">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by employee name..." value="{{ request()->query('search') }}">
                            <input type="month" name="month" class="form-control ml-2"
                                value="{{ request()->query('month', now()->format('Y-m')) }}">
                            <button type="submit" class="btn btn-secondary ml-2">Search</button>
                        </form>
                        <form method="GET" action="{{ route('payroll.exports') }}" class="ml-3">
                            <button type="submit" class="btn btn-primary">Export to CSV</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 15%">Employee Name</th>
                                <th style="width: 13%" class="text-center">Current Salary</th>
                                <th style="width: 9%" class="text-center">Total Days Worked</th>
                                <th style="width: 9%" class="text-center">Total Days Off</th>
                                <th style="width: 9%" class="text-center">Total Absent</th>
                                <th style="width: 9%" class="text-center">Total Late Check In</th>
                                <th style="width: 9%" class="text-center">Total Early Check Out</th>
                                <th style="width: 9%" class="text-center">Effective Work Days</th>
                                <th style="width: 9%" class="text-center">Overtime Pay</th>
                                <th style="width: 15%" class="text-center">Total Salary</th>
                                <th style="width: 15%" class="text-center">Validation Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payrolls as $data)
                                <tr>
                                    <td>{{ $data['employee_name'] }}</td>
                                    <td class="text-right">Rp. {{ number_format($data['current_salary'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">{{ $data['total_days_worked'] }}</td>
                                    <td class="text-center">{{ $data['total_days_off'] }}</td>
                                    <td class="text-center">{{ $data['total_absent'] }}</td>
                                    <td class="text-center">{{ $data['total_late_check_in'] }}</td>
                                    <td class="text-center">{{ $data['total_early_check_out'] }}</td>
                                    <td class="text-center">{{ $data['effective_work_days'] }}</td>
                                    <td class="text-right">Rp. {{ number_format($data['overtime_pay'], 0, ',', '.') }}</td>
                                    <td class="text-right">Rp. {{ number_format($data['total_salary'], 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if ($data['status'] === 'Pending')
                                            <form method="POST" action="{{ route('payroll.approve', $data['id']) }}"
                                                style="display: inline;" id="approve-form-{{ $data['id'] }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="button" class="btn btn-success btn-sm"
                                                    onclick="confirmApprove({{ $data['id'] }})">Approve</button>
                                            </form>
                                        @else
                                            <span class="badge badge-success">Approved</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        function confirmApprove(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to approve this payroll?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approve-form-' + id).submit();
                    Swal.fire('Approved!', 'The payroll has been approved.', 'success');
                } else {
                    Swal.fire('Cancelled', 'The payroll was not approved.', 'error');
                }
            });
        }
    </script>
@endsection
