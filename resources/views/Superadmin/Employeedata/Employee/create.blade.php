@extends('layouts.app')

@section('content')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xl-6">
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add Employee</h3>
                    </div>
                    @if ($errors->any())
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                let errorMessages = '';
                                @foreach ($errors->all() as $error)
                                    errorMessages += '{{ $error }}\n';
                                @endforeach

                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: errorMessages,
                                });
                            });
                        </script>
                    @endif
                    <form action="{{ route('employee.store') }}" method="POST">
                        @csrf

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            required value="{{ old('first_name') }}">

                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control" required
                                            value="{{ old('last_name') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" required
                                            value="{{ old('email') }}">

                                    </div>

                                    <div class="form-group">
                                        <label>Time Check-In</label>
                                        <input type="time" name="check_in_time" class="form-control" required
                                            value="{{ old('check_in_time', '10:00') }}">

                                    </div>

                                    <div class="form-group">
                                        <label>Time Check-Out</label>
                                        <input type="time" name="check_out_time" class="form-control" required
                                            value="{{ old('check_out_time', '17:00') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="place_birth">Place of Birth</label>
                                        <input type="text" name="place_birth" id="place_birth" class="form-control"
                                            required value="{{ old('place_birth') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="date_birth">Date of Birth</label>
                                        <input type="date" name="date_birth" id="date_birth" class="form-control"
                                            value="{{ old('date_birth') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="identity_number">Identity Number</label>
                                        <input type="number" name="identity_number" id="identity_number"
                                            class="form-control" value="{{ old('identity_number') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" id="address" class="form-control"
                                            value="{{ old('address') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="current_address">Current Address</label>
                                        <input type="text" name="current_address" id="current_address"
                                            class="form-control" value="{{ old('current_address') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="blood_type">Blood Type</label>
                                        <select name="blood_type" id="blood_type" class="form-control">
                                            <option value="" {{ old('blood_type') == '' ? 'selected' : '' }}>Select
                                            </option>
                                            <option value="A" {{ old('blood_type') == 'A' ? 'selected' : '' }}>A
                                            </option>
                                            <option value="B" {{ old('blood_type') == 'B' ? 'selected' : '' }}>B
                                            </option>
                                            <option value="AB" {{ old('blood_type') == 'AB' ? 'selected' : '' }}>AB
                                            </option>
                                            <option value="O" {{ old('blood_type') == 'O' ? 'selected' : '' }}>O
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="blood_rhesus">Blood Rhesus</label>
                                        <input type="text" name="blood_rhesus" id="blood_rhesus" class="form-control"
                                            value="{{ old('blood_rhesus') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="number" name="phone_number" id="phone_number" class="form-control"
                                            value="{{ old('phone_number') }}">

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hp_number">HP Number</label>
                                        <input type="number" name="hp_number" id="hp_number" class="form-control"
                                            value="{{ old('hp_number') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="marital_status">Marital Status</label>
                                        <select name="marital_status" id="marital_status" class="form-control">
                                            <option value="" {{ old('marital_status') == '' ? 'selected' : '' }}>
                                                Select</option>
                                            <option value="Single"
                                                {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married"
                                                {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Widow"
                                                {{ old('marital_status') == 'Widow' ? 'selected' : '' }}>Widow</option>
                                            <option value="Widower"
                                                {{ old('marital_status') == 'Widower' ? 'selected' : '' }}>Widower</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="last_education">Last Education</label>
                                        <select name="last_education" id="last_education" class="form-control">
                                            <option value="" {{ old('last_education') == '' ? 'selected' : '' }}>
                                                Select</option>
                                            <option value="Elementary School"
                                                {{ old('last_education') == 'Elementary School' ? 'selected' : '' }}>
                                                Elementary School</option>
                                            <option value="Junior High School"
                                                {{ old('last_education') == 'Junior High School' ? 'selected' : '' }}>
                                                Junior High School</option>
                                            <option value="Senior High School"
                                                {{ old('last_education') == 'Senior High School' ? 'selected' : '' }}>
                                                Senior High School</option>
                                            <option value="Vocational High School"
                                                {{ old('last_education') == 'Vocational High School' ? 'selected' : '' }}>
                                                Vocational High School</option>
                                            <option value="Associate Degree 1"
                                                {{ old('last_education') == 'Associate Degree 1' ? 'selected' : '' }}>
                                                Associate Degree 1</option>
                                            <option value="Associate Degree 2"
                                                {{ old('last_education') == 'Associate Degree 2' ? 'selected' : '' }}>
                                                Associate Degree 2</option>
                                            <option value="Associate Degree 3"
                                                {{ old('last_education') == 'Associate Degree 3' ? 'selected' : '' }}>
                                                Associate Degree 3</option>
                                            <option value="Bachelors Degree"
                                                {{ old('last_education') == 'Bachelors Degree' ? 'selected' : '' }}>
                                                Bachelors Degree</option>
                                            <option value="Masters Degree"
                                                {{ old('last_education') == 'Masters Degree' ? 'selected' : '' }}>Masters
                                                Degree</option>
                                            <option value="Doctoral Degree"
                                                {{ old('last_education') == 'Doctoral Degree' ? 'selected' : '' }}>Doctoral
                                                Degree</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="degree">Degree</label>
                                        <input type="text" name="degree" id="degree" class="form-control"
                                            value="{{ old('degree') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="starting_date">Starting Date</label>
                                        <input type="date" name="starting_date" id="starting_date"
                                            class="form-control" value="{{ old('starting_date') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="interview_by">Interviewed By</label>
                                        <input type="text" name="interview_by" id="interview_by" class="form-control"
                                            value="{{ old('interview_by') }}">


                                    </div>

                                    <div class="form-group">
                                        <label for="current_salary">Current Salary</label>
                                        <input type="text" name="current_salary" id="current_salary"
                                            class="form-control" value="{{ old('current_salary') }}"
                                            oninput="formatCurrency(this)" required>

                                    </div>
                                    <div class="form-group">
                                        <label for="insurance">Insurance</label>
                                        <select name="insurance" id="insurance" class="form-control">
                                            <option value="">Select</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="serious_illness">Serious Illness</label>
                                        <input type="text" name="serious_illness" id="serious_illness"
                                            class="form-control" value="{{ old('serious_illness') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="hereditary_disease">Hereditary Disease</label>
                                        <input type="text" name="hereditary_disease" id="hereditary_disease"
                                            class="form-control" value="{{ old('hereditary_disease') }}">

                                    </div>

                                    <div class="form-group">
                                        <label for="emergency_contact">Emergency Contact</label>
                                        <input type="text" name="emergency_contact" id="emergency_contact"
                                            class="form-control" value="{{ old('emergency_contact') }}">


                                    </div>

                                    <div class="form-group">
                                        <label for="relations">Relations</label>
                                        <select name="relations" id="relations" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Parent" {{ old('relations') == 'Parent' ? 'selected' : '' }}>
                                                Parent</option>
                                            <option value="Guardian"
                                                {{ old('relations') == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                                            <option value="Husband" {{ old('relations') == 'Husband' ? 'selected' : '' }}>
                                                Husband</option>
                                            <option value="Wife" {{ old('relations') == 'Wife' ? 'selected' : '' }}>Wife
                                            </option>
                                            <option value="Sibling" {{ old('relations') == 'Sibling' ? 'selected' : '' }}>
                                                Sibling</option>
                                        </select>

                                    </div>

                                    <div class="form-group">
                                        <label for="emergency_number">Emergency Number</label>
                                        <input type="number" name="emergency_number" id="emergency_number"
                                            class="form-control" value="{{ old('emergency_number') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    @if ($errors->any())
        <script>
            let errors = @json($errors->all()); // Mengambil semua pesan error sebagai array
            let errorMessages = errors.join('<br>'); // Menggabungkan pesan error menjadi satu string

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: errorMessages, // Menampilkan semua pesan error dalam bentuk HTML
                footer: '<a href="#">Why do I have this issue?</a>'
            });
        </script>
    @endif
@endsection
