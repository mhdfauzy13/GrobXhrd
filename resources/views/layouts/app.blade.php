<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('dist/img/icongrob.png') }}" type="image/x-icon" />
    <title>@yield('title', 'Grobmedia')</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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

</body>

</html>
