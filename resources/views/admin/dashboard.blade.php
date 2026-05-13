@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Selamat datang, {{ auth()->user()->name }} 👋</div>
        <div class="page-subtitle">Ringkasan sistem Smart-Hub Management hari ini</div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.inventories.create') }}" class="btn btn-secondary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Inventaris
        </a>
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Jadwal Baru
        </a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef0fc;">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#5e6ad2" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
            </svg>
        </div>
        <div class="stat-label">Total Inventaris</div>
        <div class="stat-value">{{ $totalInventories }}</div>
        <div class="stat-delta">Workspace & peralatan</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-label">Tersedia</div>
        <div class="stat-value" style="color:#16a34a;">{{ $availableInventories }}</div>
        <div class="stat-delta">Siap digunakan</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef2f2;">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="stat-label">Maintenance</div>
        <div class="stat-value" style="color:#dc2626;">{{ $maintenanceInventories }}</div>
        <div class="stat-delta">Perlu perhatian</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fffbeb;">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="stat-label">Jadwal Aktif</div>
        <div class="stat-value" style="color:#d97706;">{{ $activeSchedules }}</div>
        <div class="stat-delta">Sedang berjalan</div>
    </div>
</div>

<!-- Recent Tables -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

    <!-- Recent Inventories -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Inventaris Terbaru</div>
            <a href="{{ route('admin.inventories.index') }}" class="btn btn-ghost btn-sm">Lihat semua →</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentInventories as $inv)
                    <tr>
                        <td style="font-weight:500;">{{ $inv->name }}</td>
                        <td>
                            <span class="badge {{ $inv->type === 'workspace' ? 'badge-info' : 'badge-neutral' }}">
                                {{ $inv->type === 'workspace' ? 'Workspace' : 'Equipment' }}
                            </span>
                        </td>
                        <td>
                            @if($inv->status === 'available')
                                <span class="badge badge-success">Tersedia</span>
                            @elseif($inv->status === 'maintenance')
                                <span class="badge badge-danger">Maintenance</span>
                            @else
                                <span class="badge badge-warning">Dipinjam</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:24px;">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Schedules -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Jadwal Terbaru</div>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-ghost btn-sm">Lihat semua →</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Inventaris</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSchedules as $sched)
                    <tr>
                        <td style="font-weight:500;">{{ $sched->user->name ?? '-' }}</td>
                        <td style="color:var(--text-secondary);">{{ $sched->inventory->name ?? '-' }}</td>
                        <td>
                            @if($sched->status === 'confirmed')
                                <span class="badge badge-success">Confirmed</span>
                            @elseif($sched->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-neutral">Cancelled</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:24px;">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
