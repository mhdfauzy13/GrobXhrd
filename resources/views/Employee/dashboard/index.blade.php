@extends('layouts.app')

@section('content')
    <div>
        <h1>HALOO INI DASHBOARD EMPLOYEE</h1>

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
@endsection
