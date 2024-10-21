@extends('layouts.app')

@section('content')

    <div class="content">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-xl-6">
                        <h1>Create Employee Book</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create Employee Book</h3>
                    </div>

                    <form action="{{ route('superadmin.employeebooks.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <ul class="nav nav-tabs" id="categoryTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="violation-tab" data-toggle="tab" href="#violation"
                                        role="tab" aria-controls="violation" aria-selected="true">Violation</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="warning-tab" data-toggle="tab" href="#warning" role="tab"
                                        aria-controls="warning" aria-selected="false">Warning</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="reprimand-tab" data-toggle="tab" href="#reprimand"
                                        role="tab" aria-controls="reprimand" aria-selected="false">Reprimand</a>
                                </li>
                            </ul>

                            <input type="hidden" name="category" id="category" value="violation">

                            <div id="category-info" class="alert alert-info mt-3" style="display: none;">
                                Anda sedang mengisi form untuk kategori <span id="selected-category"></span>.
                            </div>

                            <div id="form-fields" style="display: none;">
                                <div class="form-group">
                                    <label for="employee_id">Employee</label>
                                    <select name="employee_id" id="employee_id" class="form-control" required>
                                        <option value="">Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->employee_id }}">
                                                {{ $employee->first_name }} {{ $employee->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="incident_date">Incident Date</label>
                                    <input type="date" name="incident_date" id="incident_date" class="form-control"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="incident_details">Incident Details</label>
                                    <textarea name="incident_details" id="incident_details" class="form-control" rows="5" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control" rows="5"></textarea>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#categoryTab a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');

                var selectedCategory = $(this).attr('href').substring(1);
                $('#category').val(selectedCategory);
                $('#selected-category').text(selectedCategory.charAt(0).toUpperCase() + selectedCategory
                    .slice(1));

                $('#categoryTab').hide();
                $('#category-info').show();
                $('#form-fields').show();
            });
        });
    </script>
@endsection
