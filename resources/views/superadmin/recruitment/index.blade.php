@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Projects</h3>
                <div class="card-tools">
                    <a class="btn btn-primary btn-sm" href="{{ route('recruitment.create') }}">
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Date of Birth</th>
                                <th>Last Education</th>
                                <th>Last Position</th>
                                <th>CV File</th>
                                <th>Comment</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Esa Aulia</td>
                                <td>auliaesa@gmail.com</td>
                                <td>(123) 456-7890</td>
                                <td>01-01-1990</td>
                                <td>Bachelor's Degree</td>
                                <td>Project Manager</td>
                                <td><a href="#">Download CV</a></td>
                                <td>Skill kurang sesuai</td>
                                <td class="project-state"><span class="badge badge-success">Success</span></td>
                                <td class="project-actions text-right">
                                    <a class="btn btn-primary btn-sm" href="#"><i class="fas fa-folder"></i> View</a>
                                    <a class="btn btn-info btn-sm" href="#"><i class="fas fa-pencil-alt"></i> Edit</a>
                                    <a class="btn btn-danger btn-sm" href="#"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Lutfian Tasa</td>
                                <td>lutfi@gmail.com</td>
                                <td>(123) 456-7890</td>
                                <td>01-01-1990</td>
                                <td>Bachelor's Degree</td>
                                <td>Project Manager</td>
                                <td><a href="#">Download CV</a></td>
                                <td>Berkas kurang okeh</td>
                                <td class="project-state"><span class="badge badge-success">Success</span></td>
                                <td class="project-actions text-right">
                                    <a class="btn btn-primary btn-sm" href="#"><i class="fas fa-folder"></i> View</a>
                                    <a class="btn btn-info btn-sm" href="#"><i class="fas fa-pencil-alt"></i> Edit</a>
                                    <a class="btn btn-danger btn-sm" href="#"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
