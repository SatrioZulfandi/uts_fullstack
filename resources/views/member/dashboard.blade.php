@extends('layouts.member')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Selamat datang, {{ auth()->user()->name }} 👋</div>
        <div class="page-subtitle">Berikut ringkasan aktivitas Anda di Smart-Hub</div>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdfa;">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#0d9488" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
            </svg>
        </div>
        <div class="stat-label">Inventaris Tersedia</div>
        <div class="stat-value" style="color:#0d9488;">{{ $availableInventories }}</div>
        <div class="stat-delta">Siap dipinjam</div>
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
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-label">Selesai</div>
        <div class="stat-value" style="color:#16a34a;">{{ $completedSchedules }}</div>
        <div class="stat-delta">Peminjaman selesai</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef0fc;">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#5e6ad2" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div class="stat-label">Total Peminjaman</div>
        <div class="stat-value">{{ $totalSchedules }}</div>
        <div class="stat-delta">Sepanjang waktu</div>
    </div>
</div>

<!-- Upcoming Schedules -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Jadwal Mendatang</div>
        <a href="{{ route('member.schedules.index') }}" class="btn btn-ghost btn-sm">Lihat semua →</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Inventaris</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($upcomingSchedules as $schedule)
                <tr>
                    <td style="font-weight:500;">{{ $schedule->inventory->name ?? '-' }}</td>
                    <td style="color:var(--text-secondary);">{{ $schedule->start_time->format('d M Y, H:i') }}</td>
                    <td style="color:var(--text-secondary);">{{ $schedule->end_time->format('d M Y, H:i') }}</td>
                    <td>
                        @if($schedule->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($schedule->status === 'booked')
                            <span class="badge badge-success">Booked</span>
                        @elseif($schedule->status === 'checked_in')
                            <span class="badge badge-info">Checked In</span>
                        @elseif($schedule->status === 'completed')
                            <span class="badge badge-success">Completed</span>
                        @else
                            <span class="badge badge-neutral">{{ ucfirst($schedule->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('member.schedules.show', $schedule) }}" class="btn btn-ghost btn-sm">Detail →</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--text-muted);padding:40px;">
                        Belum ada jadwal mendatang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
