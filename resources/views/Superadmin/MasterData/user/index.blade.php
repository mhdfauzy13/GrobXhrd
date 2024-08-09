@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data User</h3>
                <div class="card-tools">
                    <a class="btn btn-primary btn-sm" href="{{ route('datausers.create') }}">
                        <i class="fas fa-plus"></i>
                        Create
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
                                <th style="width: 20%">Name</th>
                                <th style="width: 30%">Email</th>
                                <th>Role</th>
                                <th style="width: 20%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->roles->isNotEmpty())
                                            {{ $user->roles->pluck('name')->implode(', ') }}
                                        @else
                                            <span class="text-muted">No Role Assigned</span>
                                        @endif
                                    </td>
                                    <td class="project-actions text-right">
                                        <a class="btn btn-info btn-sm"href="{{ route('datausers.edit', ['datauser' => $user->user_id]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                            Edit
                                        </a>
                                        <form method="post"
                                            action="{{ route('datausers.destroy', ['datauser' => $user->user_id]) }}" style="display:inline;">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                                Delete
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
