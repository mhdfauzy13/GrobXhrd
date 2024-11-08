<!-- components/script.blade.php -->
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('dist/js/pages/dashboard3.js') }}"></script>
<!--  Script for Show/Hide Password -->
<script src="{{ asset('dist/js/password-toggle.js') }}"></script>


{{-- Script buat klik employee detail --}}

<style>
    .clickable-row {
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                window.location.href = this.dataset.url;
            });
        });
    });
</script>



{{-- Script buat create role --}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select/Deselect All Features
        const selectAllFeatures = document.getElementById('selectAllFeatures');
        const allFeatureCheckboxes = document.querySelectorAll('input[name="permissions[]"]');

        selectAllFeatures.addEventListener('change', function() {
            allFeatureCheckboxes.forEach((checkbox) => {
                checkbox.checked = selectAllFeatures.checked;
            });
        });

        // Select/Deselect All Role
        const selectAllRole = document.getElementById('selectAllRole');
        const roleCheckboxes = document.querySelectorAll('.role-checkbox');

        selectAllRole.addEventListener('click', function(event) {
            event.preventDefault();
            const allChecked = Array.from(roleCheckboxes).every(checkbox => checkbox.checked);
            roleCheckboxes.forEach((checkbox) => {
                checkbox.checked = !allChecked;
            });
        });

        // Select/Deselect All User
        const selectAllUser = document.getElementById('selectAllUser');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');

        selectAllUser.addEventListener('click', function(event) {
            event.preventDefault();
            const allChecked = Array.from(userCheckboxes).every(checkbox => checkbox.checked);
            userCheckboxes.forEach((checkbox) => {
                checkbox.checked = !allChecked;
            });
        });

        // Fitur Employee
        const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
        const selectAllEmployee = document.getElementById('selectAllEmployee');

        selectAllEmployee.addEventListener('click', function(event) {
            event.preventDefault();
            const allChecked = Array.from(employeeCheckboxes).every(checkbox => checkbox.checked);
            employeeCheckboxes.forEach((checkbox) => {
                checkbox.checked = !allChecked;
            });
        });

        // Fitur Payroll
        const payrollCheckboxes = document.querySelectorAll('.payroll-checkbox');
        const selectAllPayroll = document.getElementById('selectAllPayroll');

        selectAllPayroll.addEventListener('click', function(event) {
            event.preventDefault();
            const allChecked = Array.from(payrollCheckboxes).every(checkbox => checkbox.checked);
            payrollCheckboxes.forEach((checkbox) => {
                checkbox.checked = !allChecked;
            });
        });

        // Fitur Recruitment
        const recruitmentCheckboxes = document.querySelectorAll('.recruitment-checkbox');
        const selectAllRecruitment = document.getElementById('selectAllRecruitment');

        selectAllRecruitment.addEventListener('click', function(event) {
            event.preventDefault();
            const allChecked = Array.from(recruitmentCheckboxes).every(checkbox => checkbox.checked);
            recruitmentCheckboxes.forEach((checkbox) => {
                checkbox.checked = !allChecked;
            });
        });

        // Fitur Attendance
        const attendanceCheckboxes = document.querySelectorAll('.attendance-checkbox');
        const selectAllAttendance = document.getElementById('selectAllAttendance');

        selectAllAttendance.addEventListener('click', function(event) {
            event.preventDefault();
            const allChecked = Array.from(attendanceCheckboxes).every(checkbox => checkbox.checked);
            attendanceCheckboxes.forEach((checkbox) => {
                checkbox.checked = !allChecked;
            });
        });

        // Fitur Offrequest (tambahkan checkbox class di HTML)
        const offrequestCheckboxes = document.querySelectorAll('.offrequest-checkbox');
        const selectAllOffrequest = document.getElementById('selectAllOffrequest');

        selectAllOffrequest.addEventListener('click', function(event) {
            event.preventDefault();
            const allChecked = Array.from(offrequestCheckboxes).every(checkbox => checkbox.checked);
            offrequestCheckboxes.forEach((checkbox) => {
                checkbox.checked = !allChecked;
            });
        });

    });
</script> --}}

{{-- SCRIPT BUAT MODAL IMAGE --}}

<script>
    $('#imageModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var image = button.data('image');
        var modal = $(this);
        modal.find('.modal-body #modalImage').attr('src', image);
    });
</script>


<script>
    function updateValidationStatus(employeeId, status) {
        fetch(`/payroll/validate/${employeeId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                alert('Status berhasil diperbarui: ' + data.message);
            })
            .catch(error => {
                console.error('Ada masalah dengan permintaan:', error);
            });
    }

    {{-- SCRIPT BUAT CREATE EMPLOYE CURRENT SALARY --}}

        <
        script >
        function formatCurrency(input) {
            // Hapus karakter non-digit
            let value = input.value.replace(/\D/g, '');

            // Tambahkan titik sebagai pemisah ribuan
            if (value) {
                input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            } else {
                input.value = ''; // Mengosongkan input jika tidak ada nilai
            }
        }
</script>

{{-- SCRIPT BUAT UPDATE EMPLOYE CURRENT SALARY --}}

<script>
    function formatCurrency(input) {
        // Hapus karakter non-digit
        let value = input.value.replace(/\D/g, '');

        // Tambahkan titik sebagai pemisah ribuan
        if (value) {
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        } else {
            input.value = ''; // Mengosongkan input jika tidak ada nilai
        }
    }
</script>

{{-- SCRIPT BUAT FORM LATE DEDUCTIONS DAN EARLY DEDUCTIONS --}}

<script>
    function formatNumber(input) {
        // Menghapus semua karakter yang bukan angka
        let value = input.value.replace(/[^0-9]/g, '');

        // Format angka dengan titik
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        input.value = value;
    }
</script>


{{-- SCRIPT BUAT FORM SEARCH EMPLOYEE NAME --}}

@if (Route::is('overtime.create'))
    <script>
        // Data karyawan dari backend
        const employees = @json($employees);

        // Fungsi untuk memfilter karyawan berdasarkan input nama
        function filterEmployees() {
            const query = document.getElementById('employee_name').value.toLowerCase();
            const list = document.getElementById('employee-list');
            list.innerHTML = ''; // Kosongkan list sebelum diisi

            // Filter karyawan berdasarkan nama yang cocok dengan input
            const filteredEmployees = employees.filter(employee => {
                const fullName = `${employee.first_name} ${employee.last_name}`.toLowerCase();
                return fullName.includes(query);
            });

            // Tampilkan hasil pencarianf
            filteredEmployees.forEach(employee => {
                const item = document.createElement('a');
                item.classList.add('list-group-item', 'list-group-item-action');
                item.textContent = `${employee.first_name} ${employee.last_name}`; // Tampilkan nama lengkap
                item.onclick = () => selectEmployee(employee.employee_id,
                    `${employee.first_name} ${employee.last_name}`);
                list.appendChild(item);
            });

            // Tampilkan atau sembunyikan daftar berdasarkan hasil pencarian
            list.style.display = filteredEmployees.length > 0 ? 'block' : 'none';
        }

        // Fungsi untuk memilih nama karyawan dari daftar
        function selectEmployee(id, name) {
            document.getElementById('employee_name').value = name;
            document.getElementById('employee_id').value = id;
            document.getElementById('employee-list').style.display = 'none'; // Sembunyikan daftar setelah memilih
        }

        // Fungsi untuk memastikan input hanya berasal dari daftar karyawan yang ada
        document.getElementById('employee_name').addEventListener('blur', function() {
            const inputName = this.value.toLowerCase();
            const matchedEmployee = employees.find(employee => {
                const fullName = `${employee.first_name} ${employee.last_name}`.toLowerCase();
                return fullName === inputName; // Cocokkan dengan nama lengkap
            });

            if (!matchedEmployee) {
            // Tampilkan alert SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Employee name not found. Please select a name from the list.',
            });

            // Kosongkan input jika tidak ada yang cocok
            this.value = ''; 
            document.getElementById('employee_id').value = ''; // Kosongkan ID juga
        }
        });

        // Event listener untuk menangani pencarian saat mengetik
        document.getElementById('employee_name').addEventListener('keyup', filterEmployees);
    </script>
@endif





{{-- SCRIPT BUAT DURATION TIME OVERTIME --}}


<script>
    document.getElementById('duration').addEventListener('input', function() {
        const value = this.value;
        const feedback = document.getElementById('duration-feedback');

        // Memastikan bahwa input adalah angka bulat dan dalam rentang 1 hingga 8
        if (!Number.isInteger(Number(value)) || Number(value) < 1 || Number(value) > 8) {
            feedback.style.display = 'block'; // Tampilkan pesan kesalahan
            this.classList.add('is-invalid'); // Tambahkan kelas invalid
        } else {
            feedback.style.display = 'none'; // Sembunyikan pesan kesalahan
            this.classList.remove('is-invalid'); // Hapus kelas invalid
        }
    });
</script>



{{-- SCRIPT UNTUK SEARCH INPUTAN DI TABEL EMPLOYEE --}}

<script>
    // Event listener untuk menangani pencarian saat mengetik
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let query = this.value;

        // Kirimkan request AJAX jika query tidak kosong
        if (query.length > 2 || query === '') {
            fetchEmployees(query);
        }
    });

    // Fungsi untuk mendapatkan dan menampilkan data karyawan berdasarkan query
    function fetchEmployees(query) {
        fetch("{{ route('employee.index') }}?search=" + query, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            let tableBody = document.querySelector('tbody');
            tableBody.innerHTML = ''; // Bersihkan tabel

            data.employees.forEach(employee => {
                let row = `
                    <tr>
                        <td class="text-left">${employee.first_name}</td>
                        <td class="text-center">${employee.last_name}</td>
                        <td class="text-center">${employee.email}</td>
                        <td class="text-center">${employee.address}</td>
                        <td class="project-actions text-right">
                            <a class="btn btn-info btn-sm" href="/employee/${employee.employee_id}/edit">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>
                            <form method="POST" action="/employee/${employee.employee_id}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        })
        .catch(error => console.log(error));
    }
</script>


{{-- SCRIPT UNTUK PAYROLL --}}

<script>
    function validatePayroll(payrollId) {
        fetch(`/payroll/${payrollId}/validate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) location.reload();
        });
    }
</script>