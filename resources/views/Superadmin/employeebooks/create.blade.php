@extends('layouts.app')

@section('styles')
    <style>
        #employee-list {
            position: absolute;
            z-index: 1000;
            background-color: white;
            border: 1px solid #ddd;
            max-height: 200px;
            overflow-y: auto;
            width: 100%;
        }

        #employee-list .list-group-item:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary w-100">
                    <div class="card-header">
                        <h3 class="card-title">Add {{ ucfirst($category) }}</h3>
                    </div>
                    <form id="quickForm" action="{{ route('employeebooks.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="category" value="{{ $category }}">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="employee_name">Employee Name</label>
                                <input type="text" id="employee_name" class="form-control"
                                    placeholder="Type to search employee" onkeyup="filterEmployees()" required>
                                <input type="hidden" name="employee_id" id="employee_id" required>
                                <div id="employee-list" class="list-group" style="display: none;"></div>
                            </div>

                            <div class="form-group">
                                <label for="type_of">Type of</label>
                                <select name="type_of" id="type_of" class="form-control">
                                    <option value="SOP">SOP</option>
                                    <option value="Administrative">Administrative</option>
                                    <option value="Behavior">Behavior</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="incident_date">Incident Date</label>
                                <input type="date" name="incident_date" id="incident_date" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="incident_detail">Incident Detail</label>
                                <textarea name="incident_detail" id="incident_detail" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="savebooks" class="btn btn-primary" disabled>Save</button>

                            <a href="{{ route('employeebooks.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script>
        let employees = @json($employees);
        const employeeListDiv = document.getElementById('employee-list');
        const employeeIdInput = document.getElementById('employee_id');
        const employeeNameInput = document.getElementById('employee_name');
        const saveButton = document.getElementById('savebooks'); // Mendapatkan tombol Save

        function filterEmployees() {
            const query = employeeNameInput.value.toLowerCase();
            if (query.length < 2) {
                employeeListDiv.style.display = 'none';
                return;
            }

            const filteredEmployees = employees.filter(employee => {
                return (
                    employee.first_name.toLowerCase().includes(query) ||
                    employee.last_name.toLowerCase().includes(query)
                );
            });

            employeeListDiv.innerHTML = '';
            if (filteredEmployees.length > 0) {
                employeeListDiv.style.display = 'block';
                filteredEmployees.forEach(employee => {
                    const listItem = document.createElement('a');
                    listItem.classList.add('list-group-item', 'list-group-item-action');
                    listItem.textContent = `${employee.first_name} ${employee.last_name}`;
                    listItem.onclick = function() {
                        employeeNameInput.value = `${employee.first_name} ${employee.last_name}`;
                        employeeIdInput.value = employee.employee_id;
                        employeeListDiv.style.display = 'none';
                        validateForm(); // Pastikan tombol diupdate saat memilih employee
                    };
                    employeeListDiv.appendChild(listItem);
                });
            } else {
                employeeListDiv.style.display = 'none';
            }
        }

        // Fungsi untuk memastikan tombol Save hanya aktif jika semua field valid
        function validateForm() {
            const employeeId = employeeIdInput.value;
            const incidentDate = document.getElementById('incident_date').value;
            const incidentDetail = document.getElementById('incident_detail').value;
            const remarks = document.getElementById('remarks').value;

            // Memastikan semua field sudah diisi
            if (employeeId && incidentDate && incidentDetail && remarks) {
                saveButton.disabled = false; // Aktifkan tombol Save
            } else {
                saveButton.disabled = true; // Nonaktifkan tombol Save jika ada field yang kosong
            }
        }

        // Tambahkan event listener untuk validasi form setiap kali ada perubahan
        document.getElementById('incident_date').addEventListener('change', validateForm);
        document.getElementById('incident_detail').addEventListener('input', validateForm);
        document.getElementById('remarks').addEventListener('input', validateForm);
        employeeNameInput.addEventListener('input', validateForm); // Validasi ketika nama karyawan diubah

        // Inisialisasi validasi saat halaman pertama kali dimuat
        validateForm();

        // SweetAlert dan form submit
        document.getElementById('quickForm').addEventListener('submit', function(event) {
            // Cegah form untuk langsung disubmit
            event.preventDefault();

            // Tampilkan SweetAlert sebelum mengirim form
            Swal.fire({
                position: 'top-center',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                // Setelah alert selesai, kirim form
                document.getElementById('quickForm').submit();
            });
        });
    </script>
@endsection
