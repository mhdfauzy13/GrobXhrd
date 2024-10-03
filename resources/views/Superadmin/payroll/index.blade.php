@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payroll</h3>

                <div class="card-tools">
                    <a href="{{ route('payroll.create') }}" class="btn btn-primary" title="Create Payroll">
                        <i class="fas fa-plus"></i> Create Payroll
                    </a>

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
                                    Allowance
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Overtime
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Deductions
                                </th>
                                <th style="width: 15%" class="text-center">
                                    Total Salary
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
                                <td class="text-center">{{ number_format($payroll->allowance, 2) }}</td>
                                <td class="text-center">{{ number_format($payroll->overtime, 2) }}</td>
                                <td class="text-center">{{ number_format($payroll->deductions, 2) }}</td>
                                <td class="text-center">{{ number_format($payroll->total_salary, 2) }}</td>
                                <td class="project-actions text-right">  
                                    <a class="btn btn-info btn-sm" href="{{ route('payroll.edit', $payroll->id) }}">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <form action="{{ route('payroll.destroy', $payroll->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"> 
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
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
