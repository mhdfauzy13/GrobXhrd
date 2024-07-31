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
                    {{-- <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Validation</li>
          </ol>
        </div> --}}
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
                            {{-- <div class="card-header">
                                <h3 class="card-title">Quick Example <small>jQuery Validation</small></h3>
                            </div> --}}
                            <!-- /.card-header -->
                            <!-- form start -->
                            {{-- <form id="quickForm">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Name Role</label>
                                        <input type="text" name="name" class="form-control" 
                                            placeholder="Enter Role">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" name="password" class="form-control"
                                            id="exampleInputPassword1" placeholder="Password">
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="terms" class="custom-control-input"
                                                id="exampleCheck1">
                                            <label class="custom-control-label" for="exampleCheck1">I agree to the <a
                                                    href="#">terms of service</a>.</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form> --}}


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
        <!-- /.content -->
    </div>
@endsection
