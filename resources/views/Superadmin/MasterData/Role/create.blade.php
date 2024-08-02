@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>New Role</h1>
                    </div>
                
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">

                            <form action="{{ route('role.store') }}" method="POST" id="quickForm">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="roleName">Nama Role</label>
                                        <input type="text" name="name" class="form-control" id="roleName"
                                            placeholder="Masukkan Role">
                                    </div>
                                    <div class="form-group">
                                        <label for="permissions">Permission yang Diberikan</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="permissions[]" value="create"
                                                class="custom-control-input" id="permissionCreate">
                                            <label class="custom-control-label" for="permissionCreate">Create</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="permissions[]" value="read"
                                                class="custom-control-input" id="permissionRead">
                                            <label class="custom-control-label" for="permissionRead">Read</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="permissions[]" value="update"
                                                class="custom-control-input" id="permissionUpdate">
                                            <label class="custom-control-label" for="permissionUpdate">Update</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="permissions[]" value="delete"
                                                class="custom-control-input" id="permissionDelete">
                                            <label class="custom-control-label" for="permissionDelete">Delete</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control" id="status">
                                            <option value="enable">Enable</option>
                                            <option value="disable">Disable</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>

                          

                          
                          {{-- <form action="{{ route('role.store') }}" method="POST" id="quickForm">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="roleName">Nama Role</label>
                                    <input type="text" name="name" class="form-control" id="roleName" placeholder="Masukkan Role">
                                </div>
                                <div class="form-group">
                                    <label for="permissions">Permission yang Diberikan</label>

                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Fitur Role</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="permissions[]" value="role.index" class="custom-control-input" id="roleIndex">
                                                <label class="custom-control-label" for="roleIndex">View</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="permissions[]" value="role.create" class="custom-control-input" id="roleCreate">
                                                <label class="custom-control-label" for="roleCreate">Create</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="permissions[]" value="role.edit" class="custom-control-input" id="roleEdit">
                                                <label class="custom-control-label" for="roleEdit">Edit</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="permissions[]" value="role.delete" class="custom-control-input" id="roleDelete">
                                                <label class="custom-control-label" for="roleDelete">Delete</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h3 class="card-title">Fitur User</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="permissions[]" value="user.index" class="custom-control-input" id="userIndex">
                                                <label class="custom-control-label" for="userIndex">View</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="permissions[]" value="user.create" class="custom-control-input" id="userCreate">
                                                <label class="custom-control-label" for="userCreate">Create</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="permissions[]" value="user.edit" class="custom-control-input" id="userEdit">
                                                <label class="custom-control-label" for="userEdit">Edit</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="permissions[]" value="user.delete" class="custom-control-input" id="userDelete">
                                                <label class="custom-control-label" for="userDelete">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="enable">Enable</option>
                                        <option value="disable">Disable</option>
                                    </select>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form> --}}
                        
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                    <!-- right column -->
                    <div class="col-md-6">

                    </div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
      
    </div>
@endsection

{{-- @extends('layouts.app')

@section('content')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Role</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <form action="{{ route('role.store') }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="roleName">Role Name</label>
                                        <input type="text" name="name" class="form-control" id="roleName" placeholder="Enter Role Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="permissions">Permissions</label>
                                        @foreach ($groupedPermissions as $feature => $permissions)
                                            <fieldset>
                                                <legend>{{ ucfirst($feature) }}</legend>
                                                @foreach ($permissions as $permission)
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="custom-control-input" id="permission{{ $permission->id }}">
                                                        <label class="custom-control-label" for="permission{{ $permission->id }}">{{ ucfirst($permission->name) }}</label>
                                                    </div>
                                                @endforeach
                                            </fieldset>
                                            <hr>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control" id="status" required>
                                            <option value="enable">Enable</option>
                                            <option value="disable">Disable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection --}}

