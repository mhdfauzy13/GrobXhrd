@extends('layouts.app')

@section('title', 'Employee History')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Employee History</h3>
                <div class="card-tools d-flex align-items-center">
                    <a href="{{ route('history.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add
                    </a>
                    <form action="{{ route('history.index') }}" method="GET" class="form-inline ml-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by name..."
                            value="{{ request()->query('search') }}">
                        <button type="submit" class="btn btn-secondary ml-2">Search</button>
                    </form>

                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Resign Date</th>
                                <th class="text-center">Reason</th>
                                <th class="text-center">Remarks</th>
                                <th class="text-center">Document</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($employees->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">No results found</td>
                                </tr>
                            @else
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                        <td class="text-center">{{ $employee->resign_date }}</td>
                                        <td class="text-center">{{ $employee->reason }}</td>
                                        <td class="text-center">{{ $employee->remarks }}</td>
                                        <td class="text-center">
                                            @if ($employee->document)
                                                <a href="{{ Storage::url($employee->document) }}"
                                                    target="_blank">Download</a>
                                            @else
                                                <span class="text-muted">No Document</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('history.edit', $employee->employee_id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                            <form action="{{ route('history.destroy', $employee->employee_id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this history?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="pagination-container">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
