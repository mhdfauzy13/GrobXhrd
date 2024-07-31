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
                            <tr>
                                <td>Jonas Photo</td>
                                <td>Jl. Banda No 38 Citarum, Kec. Bandung Wetan, Kota Bandung</td>
                                <td class="text-center">(123) 456-7890</td>
                                <td class="text-center">jonas.photo@gmail.com</td>
                                <td class="text-center"><span class="badge badge-success">Active</span></td>
                                <td class="project-actions text-right">
                                    <a class="btn btn-info btn-sm" href="#">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="#">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Sima Creation</td>
                                <td>Jl Sabang No 23</td>
                                <td class="text-center">(146) 234-4567</td>
                                <td class="text-center">sima.creation@gmail.com</td>
                                <td class="text-center"><span class="badge badge-success">Active</span></td>
                                <td class="project-actions text-right">
                                    <a class="btn btn-info btn-sm" href="#">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="#">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
