@extends('layouts.app')

@section('content')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xl-6">
                        <h1>Edit Employee</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        {{-- <h3 class="card-title">Edit</h3> --}}
                    </div>

                    <form action="{{ route('Employees.update', $employeeModel->employee_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', $employeeModel->first_name) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name', $employeeModel->last_name) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $employeeModel->email) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="place_birth">Place of Birth</label>
                                        <input type="text" name="place_birth" id="place_birth" class="form-control" value="{{ old('place_birth', $employeeModel->place_birth) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="date_birth">Date of Birth</label>
                                        <input type="date" name="date_birth" id="date_birth" class="form-control" value="{{ old('date_birth', $employeeModel->date_birth) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="personal_no">Personal Number</label>
                                        <input type="number" name="personal_no" id="personal_no" class="form-control" value="{{ old('personal_no', $employeeModel->personal_no) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $employeeModel->address) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="current_address">Current Address</label>
                                        <input type="text" name="current_address" id="current_address" class="form-control" value="{{ old('current_address', $employeeModel->current_address) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="blood_type">Blood Type</label>
                                        <select name="blood_type" id="blood_type" class="form-control">
                                            <option value="">Select</option>
                                            <option value="A" {{ old('blood_type', $employeeModel->blood_type) == 'A' ? 'selected' : '' }}>A</option>
                                            <option value="B" {{ old('blood_type', $employeeModel->blood_type) == 'B' ? 'selected' : '' }}>B</option>
                                            <option value="AB" {{ old('blood_type', $employeeModel->blood_type) == 'AB' ? 'selected' : '' }}>AB</option>
                                            <option value="O" {{ old('blood_type', $employeeModel->blood_type) == 'O' ? 'selected' : '' }}>O</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="blood_rhesus">Blood Rhesus</label>
                                        <input type="text" name="blood_rhesus" id="blood_rhesus" class="form-control" value="{{ old('blood_rhesus', $employeeModel->blood_rhesus) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $employeeModel->phone_number) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hp_number">HP Number</label>
                                        <input type="text" name="hp_number" id="hp_number" class="form-control" value="{{ old('hp_number', $employeeModel->hp_number) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="marital_status">Marital Status</label>
                                        <select name="marital_status" id="marital_status" class="form-control">
                                            <option value="">Select</option>
                                            <option value="single" {{ old('marital_status', $employeeModel->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ old('marital_status', $employeeModel->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                            <option value="widow" {{ old('marital_status', $employeeModel->marital_status) == 'widow' ? 'selected' : '' }}>Widow</option>
                                            <option value="widower" {{ old('marital_status', $employeeModel->marital_status) == 'widower' ? 'selected' : '' }}>Widower</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="last_education">Last Education</label>
                                        <select name="last_education" id="last_education" class="form-control">
                                            <option value="">Select</option>
                                            <option value="SD" {{ old('last_education', $employeeModel->last_education) == 'SD' ? 'selected' : '' }}>SD</option>
                                            <option value="SMP" {{ old('last_education', $employeeModel->last_education) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                            <option value="SMA" {{ old('last_education', $employeeModel->last_education) == 'SMA' ? 'selected' : '' }}>SMA</option>
                                            <option value="SMK" {{ old('last_education', $employeeModel->last_education) == 'SMK' ? 'selected' : '' }}>SMK</option>
                                            <option value="D1" {{ old('last_education', $employeeModel->last_education) == 'D1' ? 'selected' : '' }}>D1</option>
                                            <option value="D2" {{ old('last_education', $employeeModel->last_education) == 'D2' ? 'selected' : '' }}>D2</option>
                                            <option value="D3" {{ old('last_education', $employeeModel->last_education) == 'D3' ? 'selected' : '' }}>D3</option>
                                            <option value="S1" {{ old('last_education', $employeeModel->last_education) == 'S1' ? 'selected' : '' }}>S1</option>
                                            <option value="S2" {{ old('last_education', $employeeModel->last_education) == 'S2' ? 'selected' : '' }}>S2</option>
                                            <option value="S3" {{ old('last_education', $employeeModel->last_education) == 'S3' ? 'selected' : '' }}>S3</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="degree">Degree</label>
                                        <input type="text" name="degree" id="degree" class="form-control" value="{{ old('degree', $employeeModel->degree) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="starting_date">Starting Date</label>
                                        <input type="date" name="starting_date" id="starting_date" class="form-control" value="{{ old('starting_date', $employeeModel->starting_date) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="interview_by">Interviewed By</label>
                                        <input type="text" name="interview_by" id="interview_by" class="form-control" value="{{ old('interview_by', $employeeModel->interview_by) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="current_salary">Current Salary</label>
                                        <input type="number" name="current_salary" id="current_salary" class="form-control" value="{{ old('current_salary', $employeeModel->current_salary) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="insurance">Insurance</label>
                                        <select name="insurance" id="insurance" class="form-control">
                                            <option value="">Select</option>
                                            <option value="1" {{ old('insurance', $employeeModel->insurance) == '1' ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ old('insurance', $employeeModel->insurance) == '0' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="serious_illness">Serious Illness</label>
                                        <input type="text" name="serious_illness" id="serious_illness" class="form-control" value="{{ old('serious_illness', $employeeModel->serious_illness) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="hereditary_disease">Hereditary Disease</label>
                                        <input type="text" name="hereditary_disease" id="hereditary_disease" class="form-control" value="{{ old('hereditary_disease', $employeeModel->hereditary_disease) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="emergency_contact">Emergency Contact</label>
                                        <input type="text" name="emergency_contact" id="emergency_contact" class="form-control" value="{{ old('emergency_contact', $employeeModel->emergency_contact) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="relations">Relations</label>
                                        <input type="text" name="relations" id="relations" class="form-control" value="{{ old('relations', $employeeModel->relations) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="emergency_number">Emergency Number</label>
                                        <input type="text" name="emergency_number" id="emergency_number" class="form-control" value="{{ old('emergency_number', $employeeModel->emergency_number) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Select</option>
                                            <option value="active" {{ old('status', $employeeModel->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $employeeModel->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
