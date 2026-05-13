@extends('layouts.app')

@section('title', $inventory->name)
@section('breadcrumb', 'Inventaris / Detail')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ $inventory->name }}</div>
        <div class="page-subtitle">Detail informasi inventaris</div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.inventories.edit', $inventory) }}" class="btn btn-secondary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <a href="{{ route('admin.inventories.index') }}" class="btn btn-ghost">← Kembali</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:16px;align-items:start;">
    <!-- Main Info -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Informasi Umum</div>
            @if($inventory->status === 'available')
                <span class="badge badge-success">Tersedia</span>
            @elseif($inventory->status === 'maintenance')
                <span class="badge badge-danger">Maintenance</span>
            @else
                <span class="badge badge-warning">Dipinjam</span>
            @endif
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="label">Nama</div>
                    <div class="value" style="font-weight:500;">{{ $inventory->name }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Tipe</div>
                    <div class="value">
                        <span class="badge {{ $inventory->type === 'workspace' ? 'badge-info' : 'badge-neutral' }}">
                            {{ $inventory->type === 'workspace' ? 'Workspace' : 'Equipment' }}
                        </span>
                    </div>
                </div>
                <div class="detail-item" style="grid-column:span 2;">
                    <div class="label">Deskripsi</div>
                    <div class="value" style="color:var(--text-secondary);">{{ $inventory->description ?? 'Tidak ada deskripsi.' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Dibuat</div>
                    <div class="value" style="color:var(--text-secondary);">{{ $inventory->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Terakhir Diperbarui</div>
                    <div class="value" style="color:var(--text-secondary);">{{ $inventory->updated_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Quick Actions + Stats -->
    <div style="display:flex;flex-direction:column;gap:12px;">
        <div class="card">
            <div class="card-header"><div class="card-title">Aksi Cepat</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('admin.inventories.edit', $inventory) }}" class="btn btn-secondary" style="justify-content:center;">Edit Inventaris</a>
                <form method="POST" action="{{ route('admin.inventories.destroy', $inventory) }}" onsubmit="return confirm('Yakin ingin menghapus inventaris ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Inventaris
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title">Statistik</div></div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);">
                    <span style="font-size:13px;color:var(--text-secondary);">Total Peminjaman</span>
                    <span style="font-size:14px;font-weight:600;">{{ $inventory->borrowingSchedules->count() }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;">
                    <span style="font-size:13px;color:var(--text-secondary);">ID Inventaris</span>
                    <span style="font-size:13px;color:var(--text-muted);">#{{ $inventory->id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Borrowing History -->
@if($inventory->borrowingSchedules->count() > 0)
<div class="card" style="margin-top:16px;">
    <div class="card-header">
        <div class="card-title">Riwayat Peminjaman</div>
        <span style="font-size:12px;color:var(--text-muted);">{{ $inventory->borrowingSchedules->count() }} record</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Member</th><th>Mulai</th><th>Selesai</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($inventory->borrowingSchedules as $sched)
                <tr>
                    <td style="font-weight:500;">{{ $sched->user->name ?? '—' }}</td>
                    <td style="color:var(--text-secondary);">{{ $sched->start_time->format('d M Y, H:i') }}</td>
                    <td style="color:var(--text-secondary);">{{ $sched->end_time->format('d M Y, H:i') }}</td>
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
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
