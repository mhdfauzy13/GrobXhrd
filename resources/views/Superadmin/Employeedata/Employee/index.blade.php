@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Employee</h3>

                <div class="card-tools">
                    <a href="#" class="btn btn-primary" title="Create Employee">
                        <i class="fas fa-plus"></i> Create Employee
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
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                    Employee ID
                                </th>
                                <th style="width: 20%">
                                   First Name
                                </th>
                                <th style="width: 8%" class="text-center">
                                    Last Name
                                </th>
                                <th style="width: 20%" class="text-right">
                                    Email
                                </th>
                                <th style="width: 8%" class="text-center">
                                    Place Birth
                                </th>
                                <th style="width: 8%" class="text-center">
                                    Personal No
                                </th>
                                <th style="width: 8%" class="text-center">
                                    Address
                                </th>
                                <th style="width: 8%" class="text-center">
                                    Current Address
                                </th>
                                <th style="width: 8%" class="text-center">
                                    Blood Type
                                </th>
                                <th style="width: 8%" class="text-center">
                                    Blood Rhesus
                                </th>
                                <th style="width: 8%" class="text-center">
                                    Phone Number
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                          
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection



