@extends('layouts.app')
@section('title', 'Attendance/index')
@section('content')
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
                                <th style="width: 20%" class="text-left">Name</th>
                                <th style="width: 20%" class="text-center">Check-In</th>
                                <th style="width: 20%" class="text-center">Check-Out</th>
                                <th style="width: 20%" class="text-center" >Status Check-In</th>
                                <th style="width: 20%" class="text-center">Status Check-Out</th>
                                <th style="width: 20%" class="text-right">Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $attendance)
                                <tr>
                                    <td class="text-left">
                                        <a
                                            href="{{ route('attendance.recap', ['employee_id' => $attendance->employee->employee_id, 'month' => now()->format('Y-m')]) }}">
                                            {{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $attendance->check_in ? $attendance->check_in->format('H:i:s') : '-' }}</td>
                                    <td class="text-center">{{ $attendance->check_out ? $attendance->check_out->format('H:i:s') : '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $attendance->check_in_status === 'IN' ? 'success' : 'danger' }}">
                                            {{ $attendance->check_in_status }}
                                        </span>
                                    </td>
                                    <td  class="text-center">
                                        <span class="badge badge-{{ $attendance->check_out_status === 'IN' ? 'success' : 'danger' }}">
                                            {{ $attendance->check_out_status }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        @if ($attendance->image)
                                            <!-- Gambar bisa diklik untuk membuka modal -->
                                            <a href="#" data-toggle="modal" data-target="#imageModal"
                                                data-image="{{ asset('storage/' . $attendance->image) }}">
                                                <img src="{{ asset('storage/' . $attendance->image) }}"
                                                    alt="Gambar Absensi" width="100">
                                            </a>
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No data for the selected date.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="pagination-container">
                        {{ $attendances->links('vendor.pagination.adminlte') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image of Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="" alt="Gambar Absensi" class="img-fluid" id="modalImage">
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#imageModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var imageUrl = button.data('image');
            var modal = $(this);
            modal.find('#modalImage').attr('src', imageUrl);
        });
    </script>
@endsection
