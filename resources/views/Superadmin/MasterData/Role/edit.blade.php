{{-- @extends('layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Role</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                        <div class="card card-primary">



                            <form action="{{ route('role.update', $role->id) }}" method="POST" id="quickForm">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="roleName">Nama Role</label>
                                        <input type="text" name="name" class="form-control" id="roleName" value="{{ old('name', $role->name) }}" placeholder="Masukkan Role">
                                    </div>
                                    <div class="form-group">
                                        <label for="permissions">Permission yang Diberikan</label>
                                        @foreach ($permissions as $permission)
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="custom-control-input" id="permission{{ $permission->id }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="permission{{ $permission->id }}">{{ ucfirst($permission->name) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control" id="status">
                                            <option value="enable" {{ $role->status == 'enable' ? 'selected' : '' }}>Enable</option>
                                            <option value="disable" {{ $role->status == 'disable' ? 'selected' : '' }}>Disable</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>

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
 --}}

 @extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Role</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                        <div class="card card-primary">
                            <form action="{{ route('role.update', $role->id) }}" method="POST" id="quickForm">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="roleName">Nama Role</label>
                                        <input type="text" name="name" class="form-control" id="roleName"
                                            placeholder="Masukkan Role" value="{{ $role->name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="permissions">Permission yang Diberikan</label>

                                        <!-- Single Checkbox for Select/Deselect All -->
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="selectAllFeatures">
                                            <label class="custom-control-label" for="selectAllFeatures">Select All</label>
                                        </div>

                                        <!-- Fitur Role -->
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <a href="#" id="selectAllRole" class="card-title">Fitur Role</a>
                                            </div>
                                            <div class="card-body">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="permissions[]" value="role.index"
                                                        class="custom-control-input role-checkbox" id="roleIndex"
                                                        {{ in_array('role.index', $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="roleIndex">View</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="permissions[]" value="role.create"
                                                        class="custom-control-input role-checkbox" id="roleCreate"
                                                        {{ in_array('role.create', $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="roleCreate">Create</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="permissions[]" value="role.edit"
                                                        class="custom-control-input role-checkbox" id="roleEdit"
                                                        {{ in_array('role.edit', $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="roleEdit">Edit</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="permissions[]" value="role.delete"
                                                        class="custom-control-input role-checkbox" id="roleDelete"
                                                        {{ in_array('role.delete', $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="roleDelete">Delete</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Fitur User -->
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <a href="#" id="selectAllUser" class="card-title">Fitur User</a>
                                            </div>
                                            <div class="card-body">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="permissions[]" value="user.index"
                                                        class="custom-control-input user-checkbox" id="userIndex"
                                                        {{ in_array('user.index', $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="userIndex">View</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="permissions[]" value="user.create"
                                                        class="custom-control-input user-checkbox" id="userCreate"
                                                        {{ in_array('user.create', $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="userCreate">Create</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="permissions[]" value="user.edit"
                                                        class="custom-control-input user-checkbox" id="userEdit"
                                                        {{ in_array('user.edit', $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="userEdit">Edit</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="permissions[]" value="user.delete"
                                                        class="custom-control-input user-checkbox" id="userDelete"
                                                        {{ in_array('user.delete', $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="userDelete">Delete</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control" id="status">
                                            <option value="enable" {{ $role->status == 'enable' ? 'selected' : '' }}>Enable</option>
                                            <option value="disable" {{ $role->status == 'disable' ? 'selected' : '' }}>Disable</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <a href="{{ route('role.index') }}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Select/Deselect All Features
                const selectAllFeatures = document.getElementById('selectAllFeatures');
                const allFeatureCheckboxes = document.querySelectorAll('input[name="permissions[]"]');

                selectAllFeatures.addEventListener('change', function() {
                    allFeatureCheckboxes.forEach((checkbox) => {
                        checkbox.checked = selectAllFeatures.checked;
                    });
                });

                // Select/Deselect All Role
                const selectAllRole = document.getElementById('selectAllRole');
                const roleCheckboxes = document.querySelectorAll('.role-checkbox');

                selectAllRole.addEventListener('click', function(event) {
                    event.preventDefault();
                    const allChecked = Array.from(roleCheckboxes).every(checkbox => checkbox.checked);
                    roleCheckboxes.forEach((checkbox) => {
                        checkbox.checked = !allChecked;
                    });
                });

                // Select/Deselect All User
                const selectAllUser = document.getElementById('selectAllUser');
                const userCheckboxes = document.querySelectorAll('.user-checkbox');

                selectAllUser.addEventListener('click', function(event) {
                    event.preventDefault();
                    const allChecked = Array.from(userCheckboxes).every(checkbox => checkbox.checked);
                    userCheckboxes.forEach((checkbox) => {
                        checkbox.checked = !allChecked;
                    });
                });
            });
        </script>
    </div>
@endsection
