<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart-Hub') — Smart-Hub Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #ffffff;
            --bg-secondary: #f7f7f8;
            --bg-tertiary: #f0f0f2;
            --border: #e5e5e8;
            --border-hover: #c8c8cd;
            --text-primary: #1a1a1e;
            --text-secondary: #5e5e6e;
            --text-muted: #9898a8;
            --accent: #5e6ad2;
            --accent-hover: #4a55c0;
            --accent-light: #eef0fc;
            --success: #16a34a;
            --success-bg: #f0fdf4;
            --warning: #d97706;
            --warning-bg: #fffbeb;
            --danger: #dc2626;
            --danger-bg: #fef2f2;
            --sidebar-w: 240px;
            --radius: 8px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow: 0 4px 12px rgba(0,0,0,.08);
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg-secondary); color: var(--text-primary); font-size: 14px; line-height: 1.5; display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: var(--sidebar-w); background: var(--bg); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; z-index: 100; }
        .sidebar-logo { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; }
        .sidebar-logo .logo-icon { width: 28px; height: 28px; background: var(--accent); border-radius: 7px; display: flex; align-items: center; justify-content: center; }
        .sidebar-logo .logo-icon svg { color: white; }
        .sidebar-logo .logo-name { font-size: 15px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.3px; }
        .sidebar-logo .logo-sub { font-size: 11px; color: var(--text-muted); }

        .sidebar-nav { padding: 12px 10px; flex: 1; overflow-y: auto; }
        .nav-section { margin-bottom: 20px; }
        .nav-section-label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; padding: 0 10px; margin-bottom: 4px; }
        .nav-item { display: flex; align-items: center; gap: 9px; padding: 7px 10px; border-radius: 6px; color: var(--text-secondary); text-decoration: none; font-size: 13.5px; font-weight: 450; transition: all .15s ease; cursor: pointer; }
        .nav-item:hover { background: var(--bg-secondary); color: var(--text-primary); }
        .nav-item.active { background: var(--accent-light); color: var(--accent); font-weight: 500; }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }
        .nav-item .badge { margin-left: auto; background: var(--bg-tertiary); color: var(--text-muted); font-size: 11px; font-weight: 500; padding: 1px 7px; border-radius: 20px; }
        .nav-item.active .badge { background: var(--accent-light); color: var(--accent); }

        .sidebar-footer { padding: 12px 10px; border-top: 1px solid var(--border); }
        .user-card { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 6px; cursor: pointer; transition: background .15s; }
        .user-card:hover { background: var(--bg-secondary); }
        .user-avatar { width: 30px; height: 30px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 600; flex-shrink: 0; }
        .user-info .user-name { font-size: 13px; font-weight: 500; color: var(--text-primary); }
        .user-info .user-role { font-size: 11px; color: var(--text-muted); }

        /* Main Content */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { height: 52px; background: var(--bg); border-bottom: 1px solid var(--border); display: flex; align-items: center; padding: 0 24px; gap: 16px; position: sticky; top: 0; z-index: 50; }
        .topbar-breadcrumb { display: flex; align-items: center; gap: 6px; color: var(--text-muted); font-size: 13px; }
        .topbar-breadcrumb .current { color: var(--text-primary); font-weight: 500; }
        .topbar-breadcrumb span { color: var(--border-hover); }
        .topbar-actions { margin-left: auto; display: flex; align-items: center; gap: 8px; }

        .content { padding: 28px 28px; flex: 1; }

        /* Page Header */
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; }
        .page-title { font-size: 20px; font-weight: 700; letter-spacing: -0.4px; color: var(--text-primary); }
        .page-subtitle { font-size: 13px; color: var(--text-secondary); margin-top: 2px; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: var(--radius); font-size: 13px; font-weight: 500; cursor: pointer; transition: all .15s ease; border: 1px solid transparent; text-decoration: none; white-space: nowrap; }
        .btn svg { width: 14px; height: 14px; }
        .btn-primary { background: var(--accent); color: white; border-color: var(--accent); }
        .btn-primary:hover { background: var(--accent-hover); border-color: var(--accent-hover); }
        .btn-secondary { background: var(--bg); color: var(--text-primary); border-color: var(--border); }
        .btn-secondary:hover { background: var(--bg-secondary); border-color: var(--border-hover); }
        .btn-danger { background: var(--danger-bg); color: var(--danger); border-color: #fecaca; }
        .btn-danger:hover { background: #fee2e2; }
        .btn-ghost { background: transparent; color: var(--text-secondary); border: none; padding: 6px 10px; }
        .btn-ghost:hover { background: var(--bg-secondary); color: var(--text-primary); }
        .btn-sm { padding: 4px 10px; font-size: 12px; }

        /* Cards */
        .card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-sm); }
        .card-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-size: 14px; font-weight: 600; color: var(--text-primary); }
        .card-body { padding: 20px; }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 24px; }
        .stat-card { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; box-shadow: var(--shadow-sm); }
        .stat-label { font-size: 12px; font-weight: 500; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 8px; }
        .stat-value { font-size: 26px; font-weight: 700; letter-spacing: -0.8px; color: var(--text-primary); }
        .stat-delta { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
        .stat-icon { width: 32px; height: 32px; border-radius: 7px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }

        /* Table */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th { padding: 10px 16px; text-align: left; font-size: 11.5px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; border-bottom: 1px solid var(--border); background: var(--bg-secondary); }
        tbody td { padding: 12px 16px; font-size: 13.5px; border-bottom: 1px solid var(--border); color: var(--text-primary); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--bg-secondary); }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
        .badge-success { background: var(--success-bg); color: var(--success); }
        .badge-warning { background: var(--warning-bg); color: var(--warning); }
        .badge-danger { background: var(--danger-bg); color: var(--danger); }
        .badge-info { background: var(--accent-light); color: var(--accent); }
        .badge-neutral { background: var(--bg-tertiary); color: var(--text-secondary); }

        /* Forms */
        .form-grid { display: grid; gap: 18px; }
        .form-grid-2 { grid-template-columns: 1fr 1fr; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-label { font-size: 13px; font-weight: 500; color: var(--text-primary); }
        .form-hint { font-size: 12px; color: var(--text-muted); }
        .form-control { padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13.5px; font-family: inherit; color: var(--text-primary); background: var(--bg); transition: border-color .15s, box-shadow .15s; outline: none; }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(94,106,210,.12); }
        .form-control::placeholder { color: var(--text-muted); }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239898a8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; background-size: 14px; padding-right: 32px; }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .invalid-feedback { font-size: 12px; color: var(--danger); }

        /* Alert */
        .alert { padding: 12px 16px; border-radius: var(--radius); font-size: 13.5px; margin-bottom: 20px; border: 1px solid transparent; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: var(--success-bg); border-color: #bbf7d0; color: var(--success); }
        .alert-danger { background: var(--danger-bg); border-color: #fecaca; color: var(--danger); }

        /* Pagination */
        .pagination-wrap { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .pagination-info { font-size: 13px; color: var(--text-muted); }
        .pagination { display: flex; gap: 4px; }
        .pagination a, .pagination span { display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 10px; border-radius: 6px; font-size: 13px; font-weight: 450; text-decoration: none; border: 1px solid var(--border); color: var(--text-secondary); transition: all .15s; }
        .pagination a:hover { background: var(--bg-secondary); color: var(--text-primary); }
        .pagination .active span, .pagination span.active { background: var(--accent); color: white; border-color: var(--accent); }

        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
        .empty-state svg { width: 44px; height: 44px; margin-bottom: 14px; opacity: .4; }
        .empty-state h3 { font-size: 15px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
        .empty-state p { font-size: 13px; }

        /* Action buttons in table */
        .actions { display: flex; align-items: center; gap: 4px; }

        /* Detail view */
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .detail-item .label { font-size: 11.5px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 4px; }
        .detail-item .value { font-size: 14px; color: var(--text-primary); }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .form-grid-2 { grid-template-columns: 1fr; }
            .detail-grid { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
            </svg>
        </div>
        <div>
            <div class="logo-name">Smart-Hub</div>
            <div class="logo-sub">Management System</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-label">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-label">Manajemen</div>
            <a href="{{ route('admin.inventories.index') }}" class="nav-item {{ request()->routeIs('admin.inventories.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                Inventaris
            </a>
            <a href="{{ route('admin.schedules.index') }}" class="nav-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Jadwal Peminjaman
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">Administrator</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin-left:auto;">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;padding:4px;color:var(--text-muted);border-radius:4px;" title="Logout">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <div class="topbar-breadcrumb">
            <span>Smart-Hub</span>
            <span>/</span>
            <span class="current">@yield('breadcrumb', 'Dashboard')</span>
        </div>
        <div class="topbar-actions">
            @yield('topbar-actions')
        </div>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
