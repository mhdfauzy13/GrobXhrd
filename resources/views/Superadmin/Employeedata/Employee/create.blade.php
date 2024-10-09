@extends('layouts.app')

@section('content')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xl-6">
                        <h1>Create Employee</h1>
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
                                        <input type="time" name="check_in_time" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Time Check-Out</label>
                                        <input type="time" name="check_out_time" class="form-control" required>
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
                                        <label for="personal_no">Personal Number</label>
                                        <input type="number" name="personal_no" id="personal_no" class="form-control">
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
                                            <option value="single">Single</option>
                                            <option value="married">Married</option>
                                            <option value="widow">Widow</option>
                                            <option value="widower">Widower</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="last_education">Last Education</label>
                                        <select name="last_education" id="last_education" class="form-control">
                                            <option value="">Select</option>
                                            <option value="SD">SD</option>
                                            <option value="SMP">SMP</option>
                                            <option value="SMA">SMA</option>
                                            <option value="SMK">SMK</option>
                                            <option value="D1">D1</option>
                                            <option value="D2">D2</option>
                                            <option value="D3">D3</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
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
                                        <input type="number" name="current_salary" id="current_salary"
                                            class="form-control">
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
                                        <input type="text" name="relations" id="relations" class="form-control">
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
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
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
