@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Permohonan Cuti</h3>
                <div class="card-tools">
                    <a class="btn btn-primary btn-sm" href="{{ route('offrequest.create') }}">
                        <i class="fas fa-plus"></i> Tambah
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
                        <h5>Total Cuti Berdasarkan Tipe</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Jenis Cuti</th>
                                    <th>Total Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($totals as $total)
                                    <tr>
                                        <td>{{ $total->title }}</td>
                                        <td>{{ $total->total_days }} hari</td>
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
                                <th>Bukti Gambar</th>
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
                                        <span
                                            class="badge {{ $offrequest->status == 'approved' ? 'bg-success' : ($offrequest->status == 'rejected' ? 'bg-danger' : 'bg-secondary') }}">
                                            {{ ucfirst($offrequest->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($offrequest->image)
                                            <img src="{{ asset('uploads/' . $offrequest->image) }}" alt="Bukti Cuti"
                                                width="100" style="cursor: pointer;" data-toggle="modal"
                                                data-target="#imageModal"
                                                onclick="showImage('{{ asset('uploads/' . $offrequest->image) }}')">
                                        @else
                                            Tidak ada bukti cuti
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada permohonan cuti yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Navigasi pagination -->
                <div class="card-footer clearfix">
                    {{-- {{ $offrequests->links() }}  --}}
                </div>
            </div>
        </div>
    </section>

    <!-- Modal untuk menampilkan gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Bukti Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center"> <!-- Tambahkan text-center di sini -->
                    <img id="previewImage" src="" alt="Preview" class="img-fluid"
                        style="max-width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>

    <script>
        function showImage(src) {
            document.getElementById('previewImage').src = src;
        }
    </script>
@endsection
