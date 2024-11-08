@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Employee</h3>
                <div class="card-tools">
                    <a href="{{ route('employee.create') }}" class="btn btn-primary" title="Create Employee">
                        <i class="fas fa-plus"></i> Add
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
                                <th style="width: 20%" class="text-left">First Name</th>
                                <th style="width: 20%" class="text-center">Last Name</th>
                                <th style="width: 20%" class="text-center">Email</th>
                                <th style="width: 20%" class="text-center">Address</th>
                                <th style="width: 20%" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr data-url="{{ route('employee.show', $employee->employee_id) }}" class="clickable-row">
                                    <td class="text-left">{{ $employee->first_name }}</td>
                                    <td class="text-center">{{ $employee->last_name }}</td>
                                    <td class="text-center">{{ $employee->email }}</td>
                                    <td class="text-center">{{ $employee->address }}</td>
                                    <td class="project-actions text-right">
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('employee.edit', $employee->employee_id) }}">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('employee.destroy', $employee->employee_id) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="deletebutton btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="pagination-container">
                        {{ $employees->links('vendor.pagination.adminlte') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
