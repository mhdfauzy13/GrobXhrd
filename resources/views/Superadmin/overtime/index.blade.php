@extends('layouts.app')
@section('title', 'Overtime/index')
@section('content')
    <!-- Main content -->
    {{-- <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Overtime</h3>

                <div class="card-tools">
                    <a href="{{ route('overtime.create') }}" class="btn btn-primary" title="Create Overtime">
                        <i class="fas fa-plus"></i> Add
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
                                    Employee Name
                                </th>
                                <th style="width: 20%">
                                    Overtime Date
                                </th>
                                <th style="width: 10%" class="text-center">
                                    Duration (Hours)
                                </th>
                                <th style="width: 30%" class="text-left">
                                    Notes
                                </th>
                                <th style="width: 10%" class="text-center">Current Salary</th>

                              
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($overtimes as $overtime)
                                <tr>
                                    <td>{{ $overtime->employee->first_name }} {{ $overtime->employee->last_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($overtime->overtime_date)->format('d-m-Y') }}</td>
                                    <td class="text-center">{{ $overtime->duration }} hours</td>
                                    <td>{{ $overtime->notes }}</td>
                                    <td class="text-center">{{ number_format($overtime->employee->current_salary,  0, ',', '.') }}</td>


                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="pagination-container">
                        {{ $overtimes->links('vendor.pagination.adminlte') }}
                    </div>
                </div>
            </div>
        </div>
    </section> --}}



    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Overtime Request List</h3>
                <div class="card-tools">
                    <!-- Tombol untuk menambah pengajuan overtime -->
                    <a class="btn btn-primary btn-sm" href="{{ route('overtime.create') }}">
                        <i class="fas fa-plus"></i> Add
                    </a>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
    
            <!-- Menampilkan pesan success/error -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
    
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

    
                <!-- Tabel untuk daftar permohonan overtime -->
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Overtime Date</th>
                                <th>Duration (Hours)</th>
                                <th>Notes</th>
                                {{-- <th>Manager</th> --}}
                                <th>Current Salary</th>

                                <th>Status</th>
                                {{-- <th>Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($overtimes as $overtime)
                                <tr>
                                    <td>{{ $overtime->employee->name }}</td>
                                    <td>{{ $overtime->overtime_date }}</td>
                                    <td>{{ $overtime->duration }} hours</td>
                                    <td>{{ $overtime->notes }}</td>
                                    <td>
                                        @if($overtime->user->employee && $overtime->user->employee->current_salary)
                                            Rp {{ number_format($overtime->user->employee->current_salary, 0, ',', '.') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                                                        {{-- <td>{{ $overtime->manager ? $overtime->manager->name : 'N/A' }}</td> --}}
                                    <td>
                                        <span class="badge {{ $overtime->status == 'approved' ? 'bg-success' : ($overtime->status == 'rejected' ? 'bg-danger' : 'bg-secondary') }}">
                                            {{ ucfirst($overtime->status) }}
                                        </span>
                                    </td>
                               
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">There are no overtime requests available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
    
                <!-- Pagination -->
                {{-- <div class="card-footer clearfix">
                    <div class="pagination-container">
                        {{ $overtimes->links('vendor.pagination.adminlte') }}
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
    
@endsection
