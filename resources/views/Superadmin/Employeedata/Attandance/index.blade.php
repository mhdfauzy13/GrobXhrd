@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Attendance</h3>

                <div class="card-tools">
                    <form method="GET" action="{{ route('attandance.index') }}" class="form-inline">
                        <div class="form-group mr-2">
                            <input type="date" name="date" class="form-control" value="{{ $date }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 30%">Name</th>
                                <th style="width: 30%">Check in</th>
                                <th style="width: 30%">Check out</th>
                                <th style="width: 30%">Status</th>
                                <th style="width: 30%" class="text-center">Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</td>
                                    <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i:s') : '-' }}</td>
                                    <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i:s') : '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $attendance->status === 'IN' ? 'success' : 'danger' }}">
                                            {{ $attendance->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($attendance->image)
                                            <!-- Make the image clickable and open a modal -->
                                            <a href="#" data-toggle="modal" data-target="#imageModal"
                                                data-image="{{ asset('storage/' . $attendance->image) }}">
                                                <img src="{{ asset('storage/' . $attendance->image) }}"
                                                    alt="Attendance Image" width="100">
                                            </a>
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No data available for selected date.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal image -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Attendance Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('storage/' . $attendance->image) }}" alt="Attendance Image" class="img-fluid" id="modalImage">
                </div>
            </div>
        </div>
    </div>
@endsection

    