@extends('layouts.app')
@section('title', 'Offrequest/index')
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Leave Request List</h3>
                <div class="card-tools">
                    <a class="btn btn-primary btn-sm" href="{{ route('offrequest.create') }}">
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

            <div class="card-body">
                <!-- Menampilkan total cuti berdasarkan tipe -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Total Leave by Type</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Total Days</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($totals as $total)
                                    <tr>
                                        <td>{{ $total->title }}</td>
                                        <td>{{ $total->total_days }} day</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabel untuk daftar permohonan cuti -->
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Manager</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Start Event</th>
                                <th>End Event</th>
                                <th>Status</th>
                                <th>Picture Proof</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($offrequests as $offrequest)
                                <tr>
                                    <td>{{ $offrequest->user->name ?? 'N/A' }}</td>
                                    <td>{{ $offrequest->user->email ?? 'N/A' }}</td>
                                    <td>{{ $offrequest->manager ? $offrequest->manager->name : 'N/A' }}</td>
                                    <td>{{ $offrequest->title }}</td>
                                    <td>{{ $offrequest->description }}</td>
                                    <td>{{ $offrequest->start_event->format('Y-m-d') }}</td>
                                    <td>{{ $offrequest->end_event->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge {{ $offrequest->status == 'approved' ? 'bg-success' : ($offrequest->status == 'rejected' ? 'bg-danger' : 'bg-secondary') }}">
                                            {{ ucfirst($offrequest->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($offrequest->image)
                                            <!-- Menampilkan gambar jika ada -->
                                            <img src="{{ asset('uploads/' . $offrequest->image) }}" alt="Bukti Cuti"
                                                width="100" style="cursor: pointer;" data-toggle="modal"
                                                data-target="#imageModal"
                                                onclick="showImage('{{ asset('uploads/' . $offrequest->image) }}')">
                                        @else
                                            <!-- Menampilkan teks "No proof provided" jika tidak ada gambar -->
                                            <span class="text-muted" style="cursor: pointer;"
                                                onclick="openUploadModal({{ $offrequest->id }})">
                                                No proof provided
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">There are no leave requests available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        
                    </table>
                </div>

                <div class="card-footer clearfix">
                    <div class="pagination-container">
                        {{ $offrequests->links('vendor.pagination.adminlte') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal to upload image -->
    <div class="modal fade" id="uploadImageModal" tabindex="-1" role="dialog" aria-labelledby="uploadImageModalLabel"
        aria-hidden="true">
        <form id="uploadImageForm" method="POST" enctype="multipart/form-data"
            action="{{ route('offrequest.uploadImage', ['offrequest' => ':id']) }}">
            @csrf
            @method('PUT')
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadImageModalLabel">Upload Proof of Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="offrequest_id" name="offrequest_id">
                        <div class="form-group">
                            <label for="image">Upload Image</label>
                            <input type="file" name="image" class="form-control" id="image" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Upload</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function showImage(src) {
            // Menampilkan gambar bukti cuti
            document.getElementById('previewImage').src = src;
        }

        function openUploadModal(offrequestId) {
            const formAction = document.getElementById('uploadImageForm').action.replace(':id', offrequestId);
            document.getElementById('uploadImageForm').action = formAction;

            // Set ID permohonan cuti pada input hidden
            document.getElementById('offrequest_id').value = offrequestId;

            // Menampilkan modal upload gambar
            $('#uploadImageModal').modal('show');
        }
    </script>

@endsection
