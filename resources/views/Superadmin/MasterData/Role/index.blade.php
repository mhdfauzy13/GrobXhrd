@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Role</h3>

                <div class="card-tools">
                    <a href="{{ route('role.create') }}" class="btn btn-primary" title="Create Role">
                        <i class="fas fa-plus"></i> Create Role
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
                                <th style="width: 20%">
                                    Name
                                </th>
                                {{-- <th style="width: 20%">
                                    Permissions
                                </th> --}}
                                <th style="width: 8%" class="text-center">
                                    Status
                                </th>
                                <th style="width: 20%" class="text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                    
                            @foreach ($roles as $role)
                            <tr>  
                                <td>{{ $role->name }}</td>
                                {{-- <td>
                                    
                                    @foreach ($role->permissions as $permission)
                                        {{ $permission->name }}<br>
                                    @endforeach
                              
                                </td> --}}
                                <td class="project-state text-center">
                                    <span class="badge {{ $role->status == 'enable' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($role->status) }}
                                    </span>
                                </td>
                                <td class="project-actions text-right">  
                                    
                                    <a class="btn btn-info btn-sm" href="{{ route('role.edit', $role->id) }}">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <form action="{{ route('role.destroy', $role->id) }}" method="POST" style="display:inline;">
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
                <div class="card-footer clearfix">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection



