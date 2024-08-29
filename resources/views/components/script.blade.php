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

<script>
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



