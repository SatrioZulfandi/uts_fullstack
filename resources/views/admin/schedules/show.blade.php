@extends('layouts.app')

@section('title', 'Detail Jadwal')
@section('breadcrumb', 'Jadwal / Detail')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Detail Jadwal #{{ $schedule->id }}</div>
        <div class="page-subtitle">Informasi lengkap jadwal peminjaman</div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-secondary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-ghost">← Kembali</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:16px;align-items:start;">
    <!-- Main -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Informasi Jadwal</div>
            @if($schedule->status === 'confirmed')
                <span class="badge badge-success">Confirmed</span>
            @elseif($schedule->status === 'pending')
                <span class="badge badge-warning">Pending</span>
            @else
                <span class="badge badge-neutral">Cancelled</span>
            @endif
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="label">Member</div>
                    <div class="value" style="font-weight:500;">{{ $schedule->user->name ?? '—' }}</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">{{ $schedule->user->email ?? '' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Inventaris</div>
                    <div class="value" style="font-weight:500;">{{ $schedule->inventory->name ?? '—' }}</div>
                    @if($schedule->inventory)
                    <div style="margin-top:4px;">
                        <span class="badge {{ $schedule->inventory->type === 'workspace' ? 'badge-info' : 'badge-neutral' }}" style="font-size:11px;">
                            {{ $schedule->inventory->type }}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="detail-item">
                    <div class="label">Waktu Mulai</div>
                    <div class="value">{{ $schedule->start_time->format('d M Y') }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $schedule->start_time->format('H:i') }} WIB</div>
                </div>
                <div class="detail-item">
                    <div class="label">Waktu Selesai</div>
                    <div class="value">{{ $schedule->end_time->format('d M Y') }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $schedule->end_time->format('H:i') }} WIB</div>
                </div>
                <div class="detail-item">
                    <div class="label">Durasi</div>
                    <div class="value">{{ $schedule->start_time->diffForHumans($schedule->end_time, true) }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Dibuat</div>
                    <div class="value" style="color:var(--text-secondary);">{{ $schedule->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div style="display:flex;flex-direction:column;gap:12px;">
        <div class="card">
            <div class="card-header"><div class="card-title">Aksi</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-secondary" style="justify-content:center;">Edit Jadwal</a>
                <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Jadwal
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title">Status Inventaris</div></div>
            <div class="card-body">
                @if($schedule->inventory)
                    @if($schedule->inventory->status === 'available')
                        <span class="badge badge-success" style="font-size:13px;">Tersedia</span>
                    @elseif($schedule->inventory->status === 'maintenance')
                        <span class="badge badge-danger" style="font-size:13px;">Maintenance</span>
                    @else
                        <span class="badge badge-warning" style="font-size:13px;">Dipinjam</span>
                    @endif
                    <div style="font-size:12px;color:var(--text-muted);margin-top:8px;">
                        <a href="{{ route('admin.inventories.show', $schedule->inventory) }}" style="color:var(--accent);text-decoration:none;">Lihat detail inventaris →</a>
                    </div>
                @else
                    <span style="color:var(--text-muted);font-size:13px;">Inventaris tidak ditemukan</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
