@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Company Settings</h3>
            </div>

            <div class="card-body">
                <form
                    action="{{ isset($companyname) ? route('settings.update', $companyname->id) : route('settings.store') }}"
                    method="POST" enctype="multipart/form-data">

                    @csrf
                    @if (isset($companyname))
                        @method('PUT')
                    @endif

                    <div class="form-group mb-4">
                        <label for="name_company" class="form-label">Company Name</label>
                        <input type="text" class="form-control" name="name_company" id="name_company"
                            value="{{ isset($companyname) ? $companyname->name_company : '' }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="image" class="form-label">Company Image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image" id="image" accept="image/*">
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                        <div class="mt-2">
                            @if (isset($companyname) && $companyname->image)
                                <p>Current Image:</p>
                                <img src="{{ asset('storage/' . $companyname->image) }}" alt="Current Image"
                                    class="img-fluid img-thumbnail" width="150">
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        {{ isset($companyname) ? 'Update' : 'Create' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Card untuk form potongan gaji -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Salary Deduction Settings</h3>
            </div>

            <div class="card-body">
                <!-- Form untuk Late Deduction -->
                <form action="{{ route('settings.updateLateDeduction') }}" method="POST">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="late_deduction" class="form-label">Late Deduction</label>
                        <input type="text" class="form-control" name="late_deduction" id="late_deduction"
                            value="{{ isset($salaryDeduction) ? number_format($salaryDeduction->late_deduction, 0, ',', '.') : '' }}"
                            required onkeyup="formatNumber(this)">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </form>

                <!-- Form untuk Early Deduction -->
                <form action="{{ route('settings.updateEarlyDeduction') }}" method="POST" class="mt-4">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="early_deduction" class="form-label">Early Deduction</label>
                        <input type="text" class="form-control" name="early_deduction" id="early_deduction"
                            value="{{ isset($salaryDeduction) ? number_format($salaryDeduction->early_deduction, 0, ',', '.') : '' }}"
                            required onkeyup="formatNumber(this)">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </form>
            </div>
        </div>

        <!-- Card untuk form pengaturan hari kerja -->
        <!-- Form untuk pengaturan hari kerja -->
        <!-- Card untuk form pengaturan hari kerja -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Workday Settings</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.updateWorkdays') }}" method="POST">
                    @csrf
                    <label for="effective_days">Select Effective Workdays:</label><br>

                    @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="effective_days[]"
                                value="{{ $day }}"
                                {{ in_array($day, $workdaySetting->effective_days ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $day }}</label>
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary mt-3">Update Workdays</button>
                </form>
            </div>



    </section>
@endsection
