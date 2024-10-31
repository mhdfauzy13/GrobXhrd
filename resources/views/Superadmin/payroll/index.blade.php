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
                                    Total Late Check In
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Total Early Check Out
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Effective Work Days
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Current Salary
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Total Salary
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Validation Status
                                </th>
                                <th style="width: 15%" class="text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($payrolls as $payroll)
                                <tr>  
                                    <td>{{ $payroll->employee->name }}</td>
                                    <td class="text-center">{{ $payroll->days_present }}</td>
                                    <td class="text-center">{{ $payroll->total_leave }}</td>
                                    <td class="text-center">{{ $payroll->effective_work_days }}</td>
                                    <td class="text-center">Rp {{ number_format($payroll->current_salary, 0, ',', '.') }}</td>
                                    <td class="text-center">Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('payroll.updateStatus', $payroll->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <select name="validation_status" class="form-select" onchange="this.form.submit()">
                                                <option value="not_validated" {{ $payroll->validation_status == 'not_validated' ? 'selected' : '' }}>Not Validated</option>
                                                <option value="validated" {{ $payroll->validation_status == 'validated' ? 'selected' : '' }}>Validated</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-right">
                                        {{-- <a href="{{ route('payroll.show', $payroll->id) }}" class="btn btn-info btn-sm">View</a> --}}
                                        {{-- <a href="{{ route('payroll.edit', $payroll->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                                        {{-- <form action="{{ route('payroll.destroy', $payroll->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form> --}}
                                    </td>
                                </tr>
                            @endforeach 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
