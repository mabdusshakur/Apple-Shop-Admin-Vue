<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title></title>

    <link type="image/x-icon" href="{{ asset('/admin/favicon.ico') }}" rel="icon" />
    <link href="{{ asset('admin/css/bootstrap.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/fontawesome.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/toastify.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css') }}" rel="stylesheet" />

    {{-- <link href="{{ asset('admin/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('admin/js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('admin/js/jquery.dataTables.min.js') }}"></script> --}}

    <script src="{{ asset('admin/js/toastify-js.js') }}"></script>
    <script src="{{ asset('admin/js/axios.min.js') }}"></script>
    <script src="{{ asset('admin/js/config.js') }}"></script>
    {{-- <script src="{{ asset('admin/js/bootstrap.bundle.js') }}"></script> --}}

    {{-- For The Client Side Auth --}}
    {{-- <script src=" https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js "></script> --}}

    {{-- <script src="{{ asset('admin/js/auth.js') }}"></script> --}}
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body>

    <div class="LoadingOverlay d-none" id="loader">
        <div class="Line-Progress">
            <div class="indeterminate"></div>
        </div>
    </div>

    <div class="content" id="contentRef">
        @inertia
    </div>

    <script>
        function MenuBarClickHandler() {
            let sideNav = document.getElementById('sideNavRef');
            let content = document.getElementById('contentRef');
            if (sideNav.classList.contains("side-nav-open")) {
                sideNav.classList.add("side-nav-close");
                sideNav.classList.remove("side-nav-open");
                content.classList.add("content-expand");
                content.classList.remove("content");
            } else {
                sideNav.classList.remove("side-nav-close");
                sideNav.classList.add("side-nav-open");
                content.classList.remove("content-expand");
                content.classList.add("content");
            }
        }


        function logout() {
            axios.post("/api/auth/logout");
            setLoggedOut(); // Client side Auth
            window.location.href = '/login';
        }
    </script>

    {{-- Client side Auth
    <script src="{{ asset('admin/js/auth.js') }}"></script>
    <script>
        auth();
    </script> --}}
</body>

</html>
