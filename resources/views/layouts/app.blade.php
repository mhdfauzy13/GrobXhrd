<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    @yield('head')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AdminLTE 3 | Dashboard 3</title>

    <!-- Link to custom.css -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @include('components.head')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.navbar')


        <!-- Sidebar -->
        @include('partials.sidebar')


        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include('layouts.footer')
    </div>
    @include('components.script')

    {{-- <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
    @yield('scripts') --}}
</body>

</html>
