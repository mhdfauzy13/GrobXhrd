@extends('layouts.app')

@section('content')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xl-6">
                        <h1>Add Employee</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        {{-- <h3 class="card-title">Create</h3> --}}
                    </div>

                    <form action="{{ route('employee.store') }}" method="POST">
                        @csrf

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Time Check-In</label>
                                        <input type="time" name="check_in_time" class="form-control" required
                                            value="10:00">
                                    </div>
                                    <div class="form-group">
                                        <label>Time Check-Out</label>
                                        <input type="time" name="check_out_time" class="form-control" required
                                            value="17:00">
                                    </div>


                                    <div class="form-group">
                                        <label for="place_birth">Place of Birth</label>
                                        <input type="text" name="place_birth" id="place_birth" class="form-control"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="date_birth">Date of Birth</label>
                                        <input type="date" name="date_birth" id="date_birth" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="identity_number">Identity Number</label>
                                        <input type="text" name="identity_number" id="identity_number"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" id="address" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="current_address">Current Address</label>
                                        <input type="text" name="current_address" id="current_address"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="blood_type">Blood Type</label>
                                        <select name="blood_type" id="blood_type" class="form-control">
                                            <option value="">Select</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="AB">AB</option>
                                            <option value="O">O</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="blood_rhesus">Blood Rhesus</label>
                                        <input type="text" name="blood_rhesus" id="blood_rhesus" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" name="phone_number" id="phone_number" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hp_number">HP Number</label>
                                        <input type="text" name="hp_number" id="hp_number" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="marital_status">Marital Status</label>
                                        <select name="marital_status" id="marital_status" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Widow">Widow</option>
                                            <option value="Widower">Widower</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="last_education">Last Education</label>
                                        <select name="last_education" id="last_education" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Elementary School">Elementary School</option>
                                            <option value="Junior High School">Junior High School</option>
                                            <option value="Senior High School">Senior High School</option>
                                            <option value="Vocational High School">Vocational High School</option>
                                            <option value="Associate Degree 1">Associate Degree 1</option>
                                            <option value="Associate Degree 2">Associate Degree 2</option>
                                            <option value="Associate Degree 3">Associate Degree 3</option>
                                            <option value="Bachelors Degree">Bachelors Degree</option>
                                            <option value="Masters Degree">Masters Degree</option>
                                            <option value="Doctoral Degree">Doctoral Degree</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="degree">Degree</label>
                                        <input type="text" name="degree" id="degree" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="starting_date">Starting Date</label>
                                        <input type="date" name="starting_date" id="starting_date"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="interview_by">Interviewed By</label>
                                        <input type="text" name="interview_by" id="interview_by"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="current_salary">Current Salary</label>
                                        <input type="text" name="current_salary" id="current_salary"
                                            class="form-control" oninput="formatCurrency(this)" required>
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
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="hereditary_disease">Hereditary Disease</label>
                                        <input type="text" name="hereditary_disease" id="hereditary_disease"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="emergency_contact">Emergency Contact</label>
                                        <input type="text" name="emergency_contact" id="emergency_contact"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="relations">Relations</label>
                                        <select name="relations" id="relations" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Parent">Parent</option>
                                            <option value="Guardian">Guardian</option>
                                            <option value="Husband">Husband</option>
                                            <option value="Wife">Wife</option>
                                            <option value="Sibling">Sibling</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="emergency_number">Emergency Number</label>
                                        <input type="text" name="emergency_number" id="emergency_number"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
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
