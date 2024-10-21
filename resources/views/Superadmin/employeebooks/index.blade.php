@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Employee Books</h1>

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#violation">Violation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#warning">Warning</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#reprimand">Reprimand</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="violation" class="tab-pane fade show active">
                <h3 class="mb-3">Violation</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Employee Name</th>
                                <th>Incident Date</th>
                                <th>Detail</th>
                                <th>Remarks</th>
                                <th>Actions</th> <!-- Added column for actions -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($violations as $violation)
                                <tr>
                                    <td>{{ $violation->employee->first_name }} {{ $violation->employee->last_name }}</td>
                                    <td>{{ $violation->incident_date }}</td>
                                    <td>{{ $violation->incident_detail }}</td>
                                    <td>{{ $violation->remarks }}</td>
                                    <td>
                                        <a href="{{ route('employeebooks.edit', $violation->employeebook_id) }}"
                                            class="btn btn-warning">Edit</a>
                                        <form action="{{ route('employeebooks.destroy', $violation->employeebook_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('employeebooks.create') }}?category=violation" class="btn btn-primary">Create
                    Violation</a>
            </div>

            <div id="warning" class="tab-pane fade">
                <h3 class="mb-3">Warning</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Employee Name</th>
                                <th>Incident Date</th>
                                <th>Detail</th>
                                <th>Remarks</th>
                                <th>Actions</th> <!-- Added column for actions -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warnings as $warning)
                                <tr>
                                    <td>{{ $warning->employee->first_name }} {{ $warning->employee->last_name }}</td>
                                    <td>{{ $warning->incident_date }}</td>
                                    <td>{{ $warning->incident_detail }}</td>
                                    <td>{{ $warning->remarks }}</td>
                                    <td>
                                        <a href="{{ route('employeebooks.edit', $warning->employeebook_id) }}"
                                            class="btn btn-warning">Edit</a>
                                        <form action="{{ route('employeebooks.destroy', $warning->employeebook_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('employeebooks.create') }}?category=warning" class="btn btn-primary">Create Warning</a>
            </div>

            <div id="reprimand" class="tab-pane fade">
                <h3 class="mb-3">Reprimand</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Employee Name</th>
                                <th>Incident Date</th>
                                <th>Detail</th>
                                <th>Remarks</th>
                                <th>Actions</th> <!-- Added column for actions -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reprimands as $reprimand)
                                <tr>
                                    <td>{{ $reprimand->employee->first_name }} {{ $reprimand->employee->last_name }}</td>
                                    <td>{{ $reprimand->incident_date }}</td>
                                    <td>{{ $reprimand->incident_detail }}</td>
                                    <td>{{ $reprimand->remarks }}</td>
                                    <td>
                                        <a href="{{ route('employeebooks.edit', $reprimand->employeebook_id) }}"
                                            class="btn btn-warning">Edit</a>
                                        <form action="{{ route('employeebooks.destroy', $reprimand->employeebook_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('employeebooks.create') }}?category=reprimand" class="btn btn-primary">Create
                    Reprimand</a>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('category');

            if (category) {
                $('.nav-tabs a[href="#' + category + '"]').tab('show');
            }
        });
    </script>
@endsection
