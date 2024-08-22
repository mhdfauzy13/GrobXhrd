@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Employee Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('Employees.index') }}" class="btn btn-primary" title="Back to List">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Personal Information</h4>
                            <dl class="row">
                                <dt class="col-sm-4">First Name:</dt>
                                <dd class="col-sm-8">{{ $employee->first_name }}</dd>
                                <dt class="col-sm-4">Last Name:</dt>
                                <dd class="col-sm-8">{{ $employee->last_name }}</dd>
                                <dt class="col-sm-4">Email:</dt>
                                <dd class="col-sm-8">{{ $employee->email }}</dd>
                                <dt class="col-sm-4">Place of Birth:</dt>
                                <dd class="col-sm-8">{{ $employee->place_birth }}</dd>
                                <dt class="col-sm-4">Date of Birth:</dt>
                                <dd class="col-sm-8">{{ $employee->date_birth }}</dd>
                                <dt class="col-sm-4">Personal No:</dt>
                                <dd class="col-sm-8">{{ $employee->personal_no }}</dd>
                                <dt class="col-sm-4">Address:</dt>
                                <dd class="col-sm-8">{{ $employee->address }}</dd>
                                <dt class="col-sm-4">Current Address:</dt>
                                <dd class="col-sm-8">{{ $employee->current_address }}</dd>
                            </dl>
                            <div class="row">
                                <div class="col-md-12 text-left">
                                    <h4>QR Code Attandance</h4>
                                    {!! QrCode::size(200)->generate($employee->employee_id) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Additional Information</h4>
                            <dl class="row">
                                <dt class="col-sm-4">Blood Type:</dt>
                                <dd class="col-sm-8">{{ $employee->blood_type }}</dd>
                                <dt class="col-sm-4">Blood Rhesus:</dt>
                                <dd class="col-sm-8">{{ $employee->blood_rhesus }}</dd>
                                <dt class="col-sm-4">Phone Number:</dt>
                                <dd class="col-sm-8">{{ $employee->phone_number }}</dd>
                                <dt class="col-sm-4">HP Number:</dt>
                                <dd class="col-sm-8">{{ $employee->hp_number }}</dd>
                                <dt class="col-sm-4">Marital Status:</dt>
                                <dd class="col-sm-8">{{ $employee->marital_status }}</dd>
                                <dt class="col-sm-4">Last Education:</dt>
                                <dd class="col-sm-8">{{ $employee->last_education }}</dd>
                                <dt class="col-sm-4">Degree:</dt>
                                <dd class="col-sm-8">{{ $employee->degree }}</dd>
                                <dt class="col-sm-4">Starting Date:</dt>
                                <dd class="col-sm-8">{{ $employee->starting_date }}</dd>
                                <dt class="col-sm-4">Interview By:</dt>
                                <dd class="col-sm-8">{{ $employee->interview_by }}</dd>
                                <dt class="col-sm-4">Current Salary:</dt>
                                <dd class="col-sm-8">{{ $employee->current_salary }}</dd>
                                <dt class="col-sm-4">Insurance:</dt>
                                <dd class="col-sm-8">{{ $employee->insurance ? 'Yes' : 'No' }}</dd>
                                <dt class="col-sm-4">Serious Illness:</dt>
                                <dd class="col-sm-8">{{ $employee->serious_illness }}</dd>
                                <dt class="col-sm-4">Hereditary Disease:</dt>
                                <dd class="col-sm-8">{{ $employee->hereditary_disease }}</dd>
                                <dt class="col-sm-4">Emergency Contact:</dt>
                                <dd class="col-sm-8">{{ $employee->emergency_contact }}</dd>
                                <dt class="col-sm-4">Relations:</dt>
                                <dd class="col-sm-8">{{ $employee->relations }}</dd>
                                <dt class="col-sm-4">Emergency Number:</dt>
                                <dd class="col-sm-8">{{ $employee->emergency_number }}</dd>
                                <dt class="col-sm-4">Status:</dt>
                                <dd class="col-sm-8">{{ $employee->status }}</dd>
                            </dl>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>
@endsection
