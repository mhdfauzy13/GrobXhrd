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
                                <th style="width: 10%" class="text-center">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payrollData as $data)
                                <tr>
                                    <td>{{ $data['employee_name'] }}</td>
                                    <td class="text-right">Rp. {{ number_format($data['current_salary'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">{{ $data['total_days_worked'] }}</td>
                                    <td class="text-center">{{ $data['total_days_off'] }}</td>
                                    <td class="text-center">{{ $data['total_late_check_in'] }}</td>
                                    <td class="text-center">{{ $data['total_early_check_out'] }}</td>
                                    <td class="text-center">{{ $data['monthly_workdays'] }}</td>
                                    <td class="text-right">Rp. {{ number_format($data['overtime_pay'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-right">{{ number_format($data['total_payroll'], 0, ',', '.') }}</td>
                                    <td>
                                        {{ ucfirst($data['status']) }}
                                    </td>
                                    <td>
                                        @if ($data['status'] === 'pending')
                                            <form action="{{ route('payroll.approve', $data['id']) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('payroll.decline', $data['id']) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-danger btn-sm">Decline</button>
                                            </form>
                                        @elseif ($data['status'] === 'declined')
                                            <form action="{{ route('payroll.approve', $data['id']) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Approve</button>
                                            </form>
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
    document.addEventListener('DOMContentLoaded', function () {
    const approveButtons = document.querySelectorAll('.approve-btn');
    const declineButtons = document.querySelectorAll('.decline-btn');

    approveButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (confirm('Are you sure you want to approve this payroll?')) {
                fetch(`/payroll/approve/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload(); // Reload the page to update the status
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });

    declineButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (confirm('Are you sure you want to decline this payroll?')) {
                fetch(`/payroll/decline/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload(); // Reload the page to update the status
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});

    </script>
    
@endsection




{{-- 
<td class="text-center">
    @if ($data['status'] == 'pending')
        <form action="{{ route('payroll.updateStatus', $data['id']) }}" method="POST" style="display:inline;">
            @csrf
            @method('PATCH')
            <button type="submit" name="status" value="approved" class="btn btn-success btn-sm">Approve</button>
            <button type="submit" name="status" value="rejected" class="btn btn-danger btn-sm" onclick="return confirmDecline()">Decline</button>
        </form>
    @elseif ($data['status'] == 'approved')
        <span class="badge bg-success">Approved</span>
    @elseif ($data['status'] == 'rejected')
        <span class="badge bg-danger">Declined</span>
    @endif
</td> --}}
