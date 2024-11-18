@extends('layouts.app')

@section('content')
    <div>
        <h1>HALOO INI DASHBOARD SUPERADMIN</h1>

        {{-- Notifikasi --}}
        @if (Auth::user()->unreadNotifications->count() > 0)
            <div class="alert alert-info">
                <strong>Notifikasi:</strong>
                <ul>
                    @foreach (Auth::user()->unreadNotifications as $notification)
                        <li>
                            {{ $notification->data['status'] }}: Pengajuan cuti ID
                            #{{ $notification->data['offrequest_id'] }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="alert alert-success">
                Tidak ada notifikasi baru.
            </div>
        @endif
    </div>

    <!-- Small Boxes -->
    <div class="row">
        <!-- Small Box for Total Employees -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalEmployees }}</h3>
                    <p>Total Employees</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i> <!-- Ikon untuk menambahkan karyawan -->
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Small Box for Total Users -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-people"></i> <!-- Ikon untuk pengguna -->
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
@endsection
