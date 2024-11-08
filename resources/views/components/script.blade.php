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
<!-- Script Switch alert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


{{-- SCRIPT BUAT KLIK EMPLOYEE DETAIL DAN DELETE --}}

<style>
    .clickable-row {
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', function(event) {
                if (!event.target.closest('.deletebutton')) {
                    // Pastikan ini bukan klik pada tombol delete
                    window.location.href = this.dataset.url;
                }
            });
        });
    });

    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("deletebutton")) {
            e.preventDefault(); // Mencegah pengiriman form default
            e.stopPropagation(); // Hentikan event klik pada baris
            const form = e.target.closest("form"); // Ambil form terdekat

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    }).then(() => {
                        form.submit(); // Kirim form setelah konfirmasi
                    });
                }
            });
        }
    });
</script>


{{-- SCRIPT BUAT MODAL IMAGE --}}

<script>
    $('#imageModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var image = button.data('image');
        var modal = $(this);
        modal.find('.modal-body #modalImage').attr('src', image);
    });
</script>

{{-- SCRIPT BUAT CREATE EMPLOYE CURRENT SALARY --}}

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

{{-- SCRIPT ALERT BUAT SAVE --}}

<script>
    document.getElementById("saveButton").addEventListener("click", function(e) {
        e.preventDefault(); // Mencegah pengiriman form default

        Swal.fire({
            title: "Do you want to save the changes?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Save",
            denyButtonText: `Don't save`
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user mengkonfirmasi, submit form
                Swal.fire("Saved!", "", "success").then(() => {
                    document.getElementById("quickForm")
                        .submit(); // Kirim form setelah alert konfirmasi
                });
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
        });
    });
</script>

{{-- SCRIPT ALERT BUAT DELETE --}}

<script>
    // Menangani event klik pada tombol delete
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("deleteButton")) {
            e.preventDefault(); // Mencegah pengiriman form default
            const form = e.target.closest("form"); // Ambil form terdekat

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    }).then(() => {
                        form.submit(); // Kirim form setelah konfirmasi
                    });
                }
            });
        }
    });
</script>


{{-- SCRIPT BUAT HURUF PERTAMA KAPITAL SEMUA FORM --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textInputs = document.querySelectorAll('input[type="text"]');

        textInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (input.value.length > 0) {
                    input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1);
                }
            });
        });
    });
</script>
