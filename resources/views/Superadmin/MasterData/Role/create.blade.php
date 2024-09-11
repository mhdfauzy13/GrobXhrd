@extends('layouts.app')

@section('content')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>New Role</h1>
                    </div>
                </div>
            </div>
        </section>

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
                                            placeholder="Masukkan Role" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="permissions">Permissions</label>

                                        <!-- Select/Deselect All -->
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="selectAllPermissions">
                                            <label class="custom-control-label" for="selectAllPermissions">Select All</label>
                                        </div>

                                        <!-- Daftar Permissions -->
                                        <div class="form-group">
                                            @foreach($permissions as $permission)
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                        class="custom-control-input permission-checkbox" id="permission_{{ $permission->id }}">
                                                    <label class="custom-control-label" for="permission_{{ $permission->id }}">
                                                        {{ ucfirst($permission->name) }}
                                                    </label>
                                                </div>
                                            @endforeach
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

                                <div class="card-footer">
                                    <a href="{{ route('role.index') }}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('selectAllPermissions').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endsection
