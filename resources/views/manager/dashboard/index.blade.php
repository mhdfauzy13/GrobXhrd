@extends('layouts.app')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Persetujuan Cuti -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Persetujuan Cuti</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Karyawan</th>
                                    <th>Tanggal Cuti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($leaveRequests as $request)
                                    <tr>
                                        <td>{{ $request->employee->name }}</td>
                                        <td>{{ $request->start_date }} - {{ $request->end_date }}</td>
                                        <td>
                                            <a href="{{ route('leave.approve', $request->id) }}" class="btn btn-success">Setujui</a>
                                            <a href="{{ route('leave.reject', $request->id) }}" class="btn btn-danger">Tolak</a>
                                        </td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Laporan Tim -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Laporan Kinerja Tim</h3>
                    </div>
                    <div class="card-body">
                        <!-- Data kinerja tim ditampilkan di sini -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
