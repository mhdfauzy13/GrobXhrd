@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Offrequest List</h3>
                <div class="card-tools">
                    <a class="btn btn-primary btn-sm" href="{{ route('offrequest.create') }}">
                        <i class="fas fa-plus"></i> Create
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Start Event</th>
                                <th>End Event</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($offrequests as $offrequest)
                                <tr>
                                    <td>{{ $offrequest->name }}</td>
                                    <td>{{ $offrequest->email }}</td>
                                    <td>{{ $offrequest->mtitle }}</td>
                                    <td>{{ $offrequest->description }}</td>
                                    <td>{{ $offrequest->start_event }}</td>
                                    <td>{{ $offrequest->end_event }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
