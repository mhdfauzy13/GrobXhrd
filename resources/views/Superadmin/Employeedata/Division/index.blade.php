@extends('layouts.app')
@section('title', 'Division Index')
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <h3 class="card-title mb-0">Division</h3>

                    <div class="d-flex align-items-center">
                        <a href="{{ route('divisions.create') }}" class="btn btn-primary" title="Create Division">
                            <i class="fas fa-plus"></i> Add
                        </a>

                        <form action="{{ route('divisions.index') }}" method="GET" class="form-inline ml-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by name or description..." value="{{ request()->query('search') }}">
                            <button type="submit" class="btn btn-secondary ml-2">Search</button>
                        </form>
                    </div>
                </div>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-center">Description</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($divisions as $division)
                                <tr>
                                    <td class="text-left">{{ $division->name }}</td>
                                    <td class="text-center">
                                        {{ \Illuminate\Support\Str::limit($division->description, $limit = 50, $end = '...') }}
                                    </td>

                                    <td class="project-actions text-right">
                                        <a class="btn btn-info btn-sm" href="{{ route('divisions.edit', $division->id) }}">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>

                                        <form method="POST" action="{{ route('divisions.destroy', $division->id) }}"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="deleteButton btn btn-danger btn-sm">
                                                <i class="deleteButton fas fa-trash"></i> Delete
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
                        {{ $divisions->links('vendor.pagination.adminlte') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
