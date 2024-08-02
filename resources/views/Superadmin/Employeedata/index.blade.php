@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Attandance</h3>

                <div class="card-tools">
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
                                    Attendance ID
                                </th>
                                <th style="width: 30%">
                                    Employee ID
                                </th>
                                <th>
                                    Date Added
                                </th>
                                <th style="width: 25%" class="text-center">
                                    Date Modified
                                </th>
                                 </th>
                                <th style="width: 30%">
                                    Check in out
                                </th>
                                  </th>
                                <th style="width: 8%" class="text-center">
                                    Status
                                </th>
                                <th style="width: 20%">
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                             
                                <td>
                                    <a>
                                        AdminLTE v3
                                    </a>
                                    <br />
                                    <small>
                                        Created 
                                    </small>
                                </td>
                                <td>
                                    
                                    </ul>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                   <td>
                                    
                                </td>
                            </tr>
                            <tr>
                              
                                <td>
                                    <a>
                                        AdminLTE v3
                                    </a>
                                    <br />
                                    <small>
                                        Created 
                                    </small>
                                </td>
                                <td>
                                   
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection