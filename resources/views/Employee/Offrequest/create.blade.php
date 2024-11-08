@extends('layouts.app')

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add Off Requests</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('offrequest.store') }}" method="POST" enctype="multipart/form-data"> <!-- tambahkan enctype -->
                @csrf
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" value="{{ Auth::user()->name }}" readonly>
                </div>

                <div class="form-group">
                    <label for="title">Tipe Off</label>
                    <select class="form-control" id="title" name="title" required>
                        <option value="Sakit">Sakit</option>
                        <option value="Liburan">Liburan</option>
                        <option value="Urusan Keluarga">Urusan Keluarga</option>
                        <option value="Absence">Absence</option>
                        <option value="Personal Time">Personal Time</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" class="form-control" id="description" rows="4"
                        placeholder="Masukkan deskripsi permohonan cuti" required></textarea>
                </div>

                <div class="form-group">
                    <label for="start_event">Tanggal Mulai</label>
                    <input type="date" name="start_event" class="form-control" id="start_event" required>
                </div>

                <div class="form-group">
                    <label for="end_event">Tanggal Selesai</label>
                    <input type="date" name="end_event" class="form-control" id="end_event" required>
                </div>

                <div class="form-group">
                    <label for="manager_id">Pilih Manager</label>
                    <select name="manager_id" id="manager_id" class="form-control" required>
                        <option value="">Pilih Manager</option>
                        @foreach ($approvers as $approver)
                            <option value="{{ $approver->user_id }}">{{ $approver->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Upload Bukti Cuti</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror" id="image" required>
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-upload"></i></span>
                        </div>
                    </div>
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('offrequest.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</section>

<script>
    // Fungsi untuk mendapatkan tanggal saat ini dalam format YYYY-MM-DD
    function getCurrentDate() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    const startEventInput = document.getElementById('start_event');
    const endEventInput = document.getElementById('end_event');

    const minDate = getCurrentDate();
    startEventInput.setAttribute('min', minDate);
    endEventInput.setAttribute('min', minDate);

    startEventInput.addEventListener('click', function() {
        if (!startEventInput.value) {
            startEventInput.value = minDate;
        }
    });

    startEventInput.addEventListener('change', function() {
        const startDate = startEventInput.value;
        endEventInput.setAttribute('min', startDate);
        if (!endEventInput.value || endEventInput.value < startDate) {
            endEventInput.value = startDate;
        }
    });

    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("image").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endsection
