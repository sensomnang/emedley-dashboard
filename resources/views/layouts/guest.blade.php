<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Welcome') - Emedley</title>
    <script>
    (function () {
        try {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
        } catch (e) {}
    })();
</script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">

            {{-- LEFT: Banner --}}
            <div class="auth-side d-none d-lg-flex">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-5">
                        <i class="bi bi-hexagon-fill fs-3"></i>
                        <span class="fs-4 fw-bold">Emedley</span>
                    </div>
                    <small class="opacity-75">Academic Year 2022-2023</small>
                    <h3 class="fw-bold mt-2">
                        Overall Student Performance<br>Dashboard
                    </h3>
                    <p class="opacity-75 mt-3 mb-0">
                        Track students, attendance and teacher satisfaction in one place.
                    </p>
                </div>
                <i class="bi bi-bar-chart-line-fill auth-side-icon"></i>
            </div>

            {{-- RIGHT: Form --}}
            <div class="auth-form">
                <div class="d-flex d-lg-none align-items-center gap-2 mb-4">
                    <i class="bi bi-hexagon-fill text-success fs-4"></i>
                    <span class="fs-5 fw-bold">Emedley</span>
                </div>

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>