<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Emedley') - Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<body>
    <div class="app-wrapper d-flex">

        {{-- SIDEBAR --}}
        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="bi bi-hexagon-fill text-success"></i>
                <span>Emedley</span>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}"
                   class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i> Dashboard
                </a>
                <a href="{{ route('students.index') }}"
                   class="menu-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Students profile
                </a>
                <a href="{{ route('master-info.index') }}"
                    class="menu-item {{ request()->routeIs('master-info.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i> Master info
                 </a>
                <a href="{{ route('financial.index') }}"
                    class="menu-item {{ request()->routeIs('financial.*') ? 'active' : '' }}">
                        <i class="bi bi-wallet2"></i> Financial
                    </a>
                <a href="#" class="menu-item"><i class="bi bi-graph-up-arrow"></i> Improvement</a>
                <a href="#" class="menu-item"><i class="bi bi-book"></i> Course resources</a>
            </nav>

            <div class="sidebar-bottom">
                <a href="{{ route('profile.edit') }}" class="menu-item"><i class="bi bi-gear"></i> Settings</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-item w-100 border-0 bg-transparent text-start">
                        <i class="bi bi-box-arrow-left"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="main-content flex-grow-1">

            {{-- TOPBAR --}}
            <header class="topbar">
                <div>
                    <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
                    <small class="text-muted">{{ now()->format('d F, Y') }}</small>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                    <button class="icon-btn" id="themeToggle" type="button" title="Toggle dark mode">
                        <i class="bi bi-moon" id="themeIcon"></i>
                    </button>
                    <button class="icon-btn"><i class="bi bi-bell"></i></button>
                    <div class="d-flex align-items-center gap-2">
                        <img src="https://png.pngtree.com/png-clipart/20241127/original/pngtree-cartoon-user-avatar-vector-png-image_17335631.png" class="rounded-circle" width="40" height="40" alt="user">
                        <div class="lh-1">
                            <div class="fw-semibold small">{{ Auth::user()->name }}</div>
                            <small class="text-muted">{{ Auth::user()->email }}</small>
                        </div>
                    </div>
                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <main class="page-content">
                @yield('content')
            </main>
        </div>
    </div>
        <x-toast />
<script>
    const root = document.documentElement;
    const toggleBtn = document.getElementById('themeToggle');
    const icon = document.getElementById('themeIcon');

    function applyIcon() {
        const dark = root.getAttribute('data-bs-theme') === 'dark';
        icon.className = dark ? 'bi bi-sun' : 'bi bi-moon';
    }

    toggleBtn.addEventListener('click', () => {
        const next = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-bs-theme', next);
        try { localStorage.setItem('theme', next); } catch (e) {}
        applyIcon();
        updateChartTheme();
    });

    applyIcon();

    function updateChartTheme() {
        if (typeof Chart === 'undefined') return;
        const dark = root.getAttribute('data-bs-theme') === 'dark';
        const color = dark ? '#9aa59d' : '#666';
        const grid  = dark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';

        Chart.defaults.color = color;
        Chart.defaults.borderColor = grid;

        Object.values(Chart.instances).forEach(chart => chart.update());
    }

    window.addEventListener('load', updateChartTheme);
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>