<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    <title>AdminLTE 3 | Dashboard 3</title>
    @include('components.head')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('layouts.navbar')

        <!-- Main Sidebar Container -->
        @if (auth()->user()->hasRole('superadmin'))
            @include('partials.sidebar-admin')
        @else
            @include('partials.sidebar-user')
        @endif

    <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </div>
    @include('components.script')
</body>

</html>
