<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'People Management System') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @livewireStyles
    <style>
        :root {
            --pms-primary:      #2563eb;
            --pms-primary-dark: #1d4ed8;
            --pms-nav-bg:       #0f172a;
            --pms-surface:      #f1f5f9;
            --pms-border:       #e2e8f0;
            --pms-text:         #1e293b;
            --pms-muted:        #64748b;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--pms-surface);
            color: var(--pms-text);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* ── Navbar ────────────────────────────────────────────── */
        .pms-nav {
            background: var(--pms-nav-bg);
            padding: 0.65rem 0;
        }
        .pms-nav .navbar-brand {
            font-weight: 700;
            font-size: 1rem;
            color: #fff;
            letter-spacing: -0.01em;
            gap: 0.4rem;
        }
        .pms-nav .navbar-brand .bi {
            color: #60a5fa;
        }
        .pms-nav .nav-link {
            color: rgba(255,255,255,0.65) !important;
            font-size: 0.855rem;
            font-weight: 500;
            padding: 0.45rem 0.75rem !important;
            border-radius: 6px;
            transition: all .15s;
        }
        .pms-nav .nav-link:hover,
        .pms-nav .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.08);
        }
        .pms-nav .nav-user {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.45);
        }
        .pms-nav .btn-logout {
            font-size: 0.8rem;
            padding: 0.35rem 0.9rem;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.8);
            background: transparent;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
        }
        .pms-nav .btn-logout:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.45);
            color: #fff;
        }

        /* ── Cards ─────────────────────────────────────────────── */
        .card {
            border: 1px solid var(--pms-border);
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.04);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--pms-border);
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.25rem;
        }
        .card-footer {
            background: #fafafa;
            border-top: 1px solid var(--pms-border);
            border-radius: 0 0 12px 12px !important;
        }

        /* ── Form controls ─────────────────────────────────────── */
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #cbd5e1;
            font-size: 0.875rem;
            padding: 0.5rem 0.875rem;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--pms-primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #ef4444;
        }
        .form-control.is-invalid:focus, .form-select.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
        }
        .form-label {
            font-size: 0.775rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .invalid-feedback { font-size: 0.78rem; }

        /* ── Buttons ───────────────────────────────────────────── */
        .btn {
            font-weight: 500;
            border-radius: 8px;
            font-size: 0.875rem;
        }
        .btn-primary {
            background: var(--pms-primary);
            border-color: var(--pms-primary);
        }
        .btn-primary:hover {
            background: var(--pms-primary-dark);
            border-color: var(--pms-primary-dark);
        }
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        /* ── Table ─────────────────────────────────────────────── */
        .pms-table { font-size: 0.855rem; }
        .pms-table thead th {
            background: #f8fafc;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--pms-muted);
            border-bottom: 1px solid var(--pms-border);
            white-space: nowrap;
            padding: 0.75rem 1rem;
        }
        .pms-table tbody tr {
            transition: background .1s;
        }
        .pms-table tbody tr:hover {
            background: #f8fafc;
        }
        .pms-table td {
            padding: 0.8rem 1rem;
            border-color: #f1f5f9;
            vertical-align: middle;
        }

        /* ── Badges ─────────────────────────────────────────────  */
        .badge-lang {
            display: inline-flex;
            align-items: center;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            font-weight: 600;
            font-size: 0.7rem;
            border-radius: 20px;
            padding: 0.18rem 0.6rem;
            letter-spacing: 0.01em;
            white-space: nowrap;
        }
        .badge-interest {
            display: inline-flex;
            align-items: center;
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            font-weight: 600;
            font-size: 0.68rem;
            border-radius: 20px;
            padding: 0.18rem 0.6rem;
            white-space: nowrap;
            letter-spacing: 0.01em;
        }
        .badge-overflow {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
            font-weight: 600;
            font-size: 0.68rem;
            border-radius: 20px;
            padding: 0.18rem 0.55rem;
            white-space: nowrap;
            cursor: default;
        }
        .interests-cell {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: nowrap;
            overflow: hidden;
        }

        /* ── Interest pill checkboxes ──────────────────────────── */
        .interest-pill input[type="checkbox"] { display: none; }
        .interest-pill label {
            display: inline-block;
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            border: 1.5px solid #cbd5e1;
            background: #fff;
            color: #475569;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all .15s;
            user-select: none;
        }
        .interest-pill input[type="checkbox"]:checked + label {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #2563eb;
        }
        .interest-pill label:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        /* ── Alerts ─────────────────────────────────────────────  */
        .alert {
            border-radius: 10px;
            font-size: 0.875rem;
            border: none;
        }
        .alert-success {
            background: #f0fdf4;
            color: #15803d;
            border-left: 4px solid #22c55e !important;
            border: none;
        }

        /* ── Page header ─────────────────────────────────────────  */
        .page-header {
            padding: 1.5rem 0 1.25rem;
        }
        .page-header h1 {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #0f172a;
            margin: 0;
        }
        .page-header p {
            color: var(--pms-muted);
            font-size: 0.875rem;
            margin: 0.2rem 0 0;
        }

        /* ── Misc ────────────────────────────────────────────────  */
        .section-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--pms-muted);
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--pms-border);
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="pms-nav navbar navbar-expand-md">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="bi bi-people-fill me-2"></i>{{ config('app.name') }}
                </a>
                <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" style="color:rgba(255,255,255,.6)">
                    <i class="bi bi-list fs-5"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto ms-3 gap-1">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('people.*') ? 'active' : '' }}" href="{{ route('people.index') }}">
                                    <i class="bi bi-people me-1"></i>People
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center gap-3">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <span class="nav-user">
                                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                                </span>
                            </li>
                            <li class="nav-item">
                                <a class="btn-logout" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <div class="container">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Initialise Bootstrap tooltips (and re-initialise after Livewire re-renders)
        function initTooltips() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                bootstrap.Tooltip.getOrCreateInstance(el);
            });
        }
        document.addEventListener('DOMContentLoaded', initTooltips);
        document.addEventListener('livewire:initialized', function () {
            initTooltips();

            Livewire.hook('morph.updated', function () {
                initTooltips();
            });
        });
    </script>
</body>
</html>
