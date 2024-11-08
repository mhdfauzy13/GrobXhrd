@extends('layouts.app')

@section('content')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Role</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <form action="{{ route('role.update', $role->id) }}" method="POST" id="quickForm">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="roleName">Name Role</label>
                                        <input type="text" name="name" class="form-control" id="roleName"
                                            value="{{ $role->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="permissions">Permissions Granted</label>

                                        <!-- Select/Deselect All -->
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="selectAllPermissions">
                                            <label class="custom-control-label" for="selectAllPermissions">Select All</label>
                                        </div>

                                        <!-- Fitur Dashboard -->
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <a href="#" id="selectAllDashboard" class="card-title">Fitur Dashboard</a>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($permissions->whereIn('name', ['dashboard.view', 'dashboardemployee.view']) as $permission)
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            class="custom-control-input dashboard-checkbox"
                                                            id="dashboard_{{ $permission->id }}"
                                                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="dashboard_{{ $permission->id }}">
                                                            {{ ucfirst(str_replace('dashboard.', '', $permission->name)) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Fitur Role -->
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <a href="#" id="selectAllRole" class="card-title">Fitur Role</a>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($permissions->whereIn('name', ['role.index', 'role.create', 'role.edit', 'role.delete']) as $permission)
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            class="custom-control-input role-checkbox"
                                                            id="role_{{ $permission->id }}"
                                                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="role_{{ $permission->id }}">
                                                            {{ ucfirst(str_replace('role.', '', $permission->name)) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Fitur User -->
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <a href="#" id="selectAllUser" class="card-title">Fitur User</a>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($permissions->whereIn('name', ['user.index', 'user.create', 'user.edit', 'user.delete']) as $permission)
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            class="custom-control-input user-checkbox"
                                                            id="user_{{ $permission->id }}"
                                                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="user_{{ $permission->id }}">
                                                            {{ ucfirst(str_replace('user.', '', $permission->name)) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Fitur lainnya (loop) -->
                                        @foreach ([
                                            'employee' => ['employee.index', 'employee.create', 'employee.edit', 'employee.delete'],
                                            'payroll' => ['payroll.index', 'payroll.create', 'payroll.edit', 'payroll.delete'],
                                            'recruitment' => ['recruitment.index', 'recruitment.create', 'recruitment.edit', 'recruitment.delete'],
                                            'attandance' => ['attandance.index', 'attandance.scanView', 'attandance.scan'],
                                            'offrequest' => ['offrequest.index', 'offrequest.create', 'offrequest.approver'],
                                            'employeebook' => ['employeebook.index', 'employeebook.create', 'employeebook.edit', 'employeebook.delete', 'employeebook.detail'],
                                            'event' => ['event.index', 'event.lists', 'event.create', 'event.edit', 'event.delete'],
                                            'settings' => ['settings.index', 'settings.company', 'settings.deductions', 'settings.worksdays'],
                                        ] as $feature => $featurePermissions)
                                            <div class="card mt-3">
                                                <div class="card-header">
                                                    <a href="#" id="selectAll{{ ucfirst($feature) }}" class="card-title">Fitur {{ ucfirst($feature) }}</a>
                                                </div>
                                                <div class="card-body">
                                                    @foreach ($permissions->whereIn('name', $featurePermissions) as $permission)
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" name="permissions[]"
                                                                value="{{ $permission->name }}"
                                                                class="custom-control-input {{ $feature }}-checkbox"
                                                                id="{{ $feature }}_{{ $permission->id }}"
                                                                {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="{{ $feature }}_{{ $permission->id }}">
                                                                {{ ucfirst(str_replace($feature . '.', '', $permission->name)) }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
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
                                    <a href="{{ route('role.index') }}" class="btn btn-secondary">Back</a>
                                    <button type="submit" id="saveButton" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            // Select/Deselect All Permissions
            document.getElementById('selectAllPermissions').addEventListener('click', function() {
                let checkboxes = document.querySelectorAll('.custom-control-input');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Add event listeners for each feature select all
            const features = [
                'dashboard', 'role', 'user', 'employee', 'payroll', 'recruitment', 'attandance', 
                'offrequest', 'employeebook', 'event', 'settings'
            ];

            features.forEach(feature => {
                document.getElementById(`selectAll${feature.charAt(0).toUpperCase() + feature.slice(1)}`)
                    .addEventListener('click', function(e) {
                        e.preventDefault();
                        let checkboxes = document.querySelectorAll(`.${feature}-checkbox`);
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = !checkbox.checked;
                        });
                    });
            });
        </script>
    </div>
@endsection
