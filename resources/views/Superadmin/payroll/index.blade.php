@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">

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
                                <th style="width: 25%">
                                    Employee Name
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Total Days Worked
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Total Days Off
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Current Salary
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Status Validasi
                                </th>
                                <th style="width: 15%" class="text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($payrolls as $payroll)
                            <tr>  
                                <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                                <td>{{ $payroll->total_days_worked }}</td>
                                <td>{{ $payroll->total_days_off }}</td>
                                <td>{{ $payroll->current_salary }}</td>
                                <td>{{ $payroll->is_validated ? 'Tervalidasi' : 'Belum Divalidasi' }}</td>
                                <td>
                                    @if (!$payroll->is_validated)
                                    <form action="{{ route('payroll.validate', $payroll->payroll_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Validasi</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach 
                        </tbody>

                    </table>
                </div>
                {{-- <div class="card-footer clearfix">
                    {{ $payrolls->links() }}
                </div> --}}
            </div>
        </div>
    </section>
@endsection
