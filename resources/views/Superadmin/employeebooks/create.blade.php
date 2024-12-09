@extends('layouts.app')

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
                            <!-- Employee Search -->
                            <div class="form-group">
                                <label for="employee_name">Employee Name</label>
                                <input type="text" id="employee_name" class="form-control"
                                    placeholder="Type to search employee" onkeyup="filterEmployees()" required>
                                <input type="hidden" name="employee_id" id="employee_id" required>
                                <div id="employee-list" class="list-group" style="display: none;"></div>
                            </div>
                            <!-- Type of -->
                            <div class="form-group">
                                <label for="type_of">Type of</label>
                                <select name="type_of" id="type_of" class="form-control">
                                    <option value="SOP">SOP</option>
                                    <option value="Administrative">Administrative</option>
                                    <option value="Behavior">Behavior</option>
                                </select>
                            </div>

                            <!-- Incident Date -->
                            <div class="form-group">
                                <label for="incident_date">Incident Date</label>
                                <input type="date" name="incident_date" id="incident_date" class="form-control" required>
                            </div>

                            <!-- Incident Detail -->
                            <div class="form-group">
                                <label for="incident_detail">Incident Detail</label>
                                <textarea name="incident_detail" id="incident_detail" class="form-control" required></textarea>
                            </div>

                            <!-- Remarks -->
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control" required></textarea>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer">
                            <button type="button" id="savebooks" class="btn btn-primary">Save</button>
                            <a href="{{ route('employeebooks.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <!-- Employee Search Script -->
    <script>
        function filterEmployees() {
            let query = document.getElementById("employee_name").value;

            if (query.length >= 2) {
                // Jika sudah mengetik 2 karakter atau lebih
                fetch(`/employeebooks/search/employees?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        let employeeList = document.getElementById("employee-list");
                        employeeList.innerHTML = ''; // Kosongkan daftar sebelumnya

                        if (data.length > 0) {
                            data.forEach(employee => {
                                let listItem = document.createElement("div");
                                listItem.classList.add("list-group-item");
                                listItem.textContent = employee.full_name;
                                listItem.onclick = function() {
                                    // Isi field dengan data yang dipilih
                                    document.getElementById("employee_name").value = employee.full_name;
                                    document.getElementById("employee_id").value = employee.employee_id;
                                    employeeList.style.display =
                                    "none"; // Sembunyikan daftar setelah memilih
                                };
                                employeeList.appendChild(listItem);
                            });
                            employeeList.style.display = "block"; // Tampilkan daftar
                        } else {
                            employeeList.style.display = "none"; // Tidak ada hasil
                        }
                    })
                    .catch(error => console.error('Error fetching employees:', error));
            } else {
                document.getElementById("employee-list").style.display = "none"; // Jika kurang dari 2 karakter
            }
        }
    </script>


    <!-- Save Form with SweetAlert -->
    <script>
        document.getElementById('savebooks').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Show SweetAlert after clicking Save
            Swal.fire({
                position: 'top-center',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                // Submit form after alert finishes
                document.getElementById('quickForm').submit();
            });
        });
    </script>

    <!-- Add CSS for Employee List Styling -->
@endsection
