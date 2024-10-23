@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Employee Books</h2>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="employeeBookTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="violation-tab" data-toggle="tab" href="#violation" role="tab"
                    aria-controls="violation" aria-selected="true">Violation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="warning-tab" data-toggle="tab" href="#warning" role="tab"
                    aria-controls="warning" aria-selected="false">Warning</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="reprimand-tab" data-toggle="tab" href="#reprimand" role="tab"
                    aria-controls="reprimand" aria-selected="false">Reprimand</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="employeeBookTabContent">
            <!-- Violation Tab -->
            <div id="violation" class="tab-pane fade show active">
                <h3 class="mb-3">Violation</h3>
                <a id="create-button" href="{{ route('employeebooks.create') }}?category=violation"
                    class="btn btn-primary mb-3">Create Violation</a>
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead class="thead-light">
                            <tr>
                                <th>Employee Name</th>
                                <th>Incident Date</th>
                                <th>Detail</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($violations as $violation)
                                <tr>
                                    <td>{{ $violation->employee->first_name }} {{ $violation->employee->last_name }}</td>
                                    <td>{{ $violation->incident_date }}</td>
                                    <td>{{ cutText($violation->incident_detail, 50) }}</td> <!-- Memotong teks -->
                                    <td>{{ cutText($violation->remarks, 50) }}</td> <!-- Memotong teks -->
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('employeebooks.edit', $violation->employeebook_id) }}">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <form action="{{ route('employeebooks.destroy', $violation->employeebook_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this item?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('employeebooks.detail', $violation->employeebook_id) }}">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Warning Tab -->
            <div id="warning" class="tab-pane fade">
                <h3 class="mb-3">Warning</h3>
                <a id="create-button" href="{{ route('employeebooks.create') }}?category=warning"
                    class="btn btn-primary mb-3">Create Warning</a>
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead class="thead-light">
                            <tr>
                                <th>Employee Name</th>
                                <th>Incident Date</th>
                                <th>Detail</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warnings as $warning)
                                <tr>
                                    <td>{{ $warning->employee->first_name }} {{ $warning->employee->last_name }}</td>
                                    <td>{{ $warning->incident_date }}</td>
                                    <td>{{ cutText($warning->incident_detail, 50) }}</td> <!-- Memotong teks -->
                                    <td>{{ cutText($warning->remarks, 50) }}</td> <!-- Memotong teks -->
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('employeebooks.edit', $warning->employeebook_id) }}">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <form action="{{ route('employeebooks.destroy', $warning->employeebook_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this item?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('employeebooks.detail', $warning->employeebook_id) }}">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reprimand Tab -->
            <div id="reprimand" class="tab-pane fade">
                <h3 class="mb-3">Reprimand</h3>
                <a id="create-button" href="{{ route('employeebooks.create') }}?category=reprimand"
                    class="btn btn-primary mb-3">Create Reprimand</a>
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead class="thead-light">
                            <tr>
                                <th>Employee Name</th>
                                <th>Incident Date</th>
                                <th>Detail</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reprimands as $reprimand)
                                <tr>
                                    <td>{{ $reprimand->employee->first_name }} {{ $reprimand->employee->last_name }}</td>
                                    <td>{{ $reprimand->incident_date }}</td>
                                    <td>{{ cutText($reprimand->incident_detail, 50) }}</td> <!-- Memotong teks -->
                                    <td>{{ cutText($reprimand->remarks, 50) }}</td> <!-- Memotong teks -->
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('employeebooks.edit', $reprimand->employeebook_id) }}">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <form action="{{ route('employeebooks.destroy', $reprimand->employeebook_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this item?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('employeebooks.detail', $reprimand->employeebook_id) }}">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @php
        function cutText($text, $length)
        {
            if (strlen($text) <= $length) {
                return $text;
            }
            // Memotong pada batas kata
            $words = explode(' ', $text);
            $cutText = '';
            foreach ($words as $word) {
                if (strlen($cutText) + strlen($word) + 1 > $length) {
                    break;
                }
                $cutText .= ($cutText ? ' ' : '') . $word;
            }
            return $cutText;
        }
    @endphp
@endsection
