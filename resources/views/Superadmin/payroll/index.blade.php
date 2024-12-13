@extends('layouts.app')
@section('title', 'Payroll/index')
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
                                    <td class="text-center">{{ $data['total_late_check_in'] }}</td>
                                    <td class="text-center">{{ $data['total_early_check_out'] }}</td>
                                    <td class="text-center">{{ $data['effective_work_days'] }}</td>
                                    <td class="text-right">Rp. {{ number_format($data['overtime_pay'], 0, ',', '.') }}</td>
                                    <td class="text-right">Rp. {{ number_format($data['total_salary'], 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if ($data['status'] === 'Pending')
                                            <form method="POST" action="{{ route('payroll.approve', $data['id']) }}" style="display: inline;" id="approve-form-{{ $data['id'] }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="button" class="btn btn-success btn-sm" onclick="confirmApprove({{ $data['id'] }})">Approve</button>
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

            <div class="card-footer clearfix">
                <div class="pagination-container">
                    {{-- {{ $payrollData('vendor.pagination.bootstrap-4') }} --}}
                </div>
            </div>
        </div>
     


    </section>


    {{-- @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let buttons = document.querySelectorAll('.approve-btn');
            console.log('Approve buttons:', buttons);  // Debugging log untuk memastikan tombol ada
            buttons.forEach(function (button) {
                button.addEventListener('click', function () {
                    let payrollId = this.dataset.id;
                    console.log('Clicked approve for payroll ID:', payrollId);  // Debugging log
    
                    if (confirm('Are you sure you want to approve this payroll?')) {
                        fetch(`{{ route('payroll.approve') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ id: payrollId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Response from server:', data);  // Debugging log
                            if (data.success) {
                                let button = this;
                                button.textContent = 'Approved';
                                button.classList.remove('btn-success');
                                button.classList.add('btn-secondary');
                                button.disabled = true;
    
                                let badge = button.closest('tr').querySelector('.badge');
                                badge.classList.remove('badge-warning');
                                badge.classList.add('badge-success');
                                badge.textContent = 'Approved';
                            } else {
                                alert('Failed to approve payroll. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while processing your request.');
                        });
                    }
                });
            });
        });
    </script>
    @endpush --}}




    <script>
        // JavaScript untuk memunculkan konfirmasi sebelum decline
        function confirmDecline() {
            return confirm("Are you sure you want to decline this payroll data? Please verify the data first.");
        }


        function confirmApprove(id) {
        // Menampilkan SweetAlert konfirmasi
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
                // Jika user mengkonfirmasi, submit form
                document.getElementById('approve-form-' + id).submit();
                Swal.fire(
                    'Approved!',
                    'The payroll has been approved.',
                    'success'
                );
            } else {
                // Jika user batal
                Swal.fire(
                    'Cancelled',
                    'The payroll was not approved.',
                    'error'
                );
            }
        });
    }
    </script>

@endsection
