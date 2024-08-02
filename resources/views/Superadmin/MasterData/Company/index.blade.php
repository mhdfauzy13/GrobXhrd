@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Company</h3>

                <div class="card-tools">
                    <a class="btn btn-primary btn-sm" href="{{ route('company.create') }}">
                        <i class="fas fa-plus"></i> Create
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
                                <th style="width: 15%">Name Company</th>
                                <th style="width: 20%">Address</th>
                                <th style="width: 10%" class="text-center">Phone Number</th>
                                <th style="width: 15%" class="text-center">Email</th>
                                <th style="width: 10%" class="text-center">Status</th>
                                <th style="width: 15%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $company)
                                <tr>
                                    <td>{{ $company->name_company }}</td>
                                    <td>{{ $company->address }}</td>
                                    <td class="text-center">{{ $company->phone_number }}</td>
                                    <td class="text-center">{{ $company->email }}</td>
                                    <td class="text-center">{{ $company->status }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('company.show', $company->company_id) }}">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ route('company.edit', $company->company_id) }}">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <form action="{{ route('company.destroy', $company->company_id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
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
