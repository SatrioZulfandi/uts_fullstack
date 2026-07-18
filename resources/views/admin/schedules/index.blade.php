@extends('layouts.app')

@section('title', 'Jadwal Peminjaman')
@section('breadcrumb', 'Jadwal Peminjaman')

@section('topbar-actions')
<a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
    </svg>
    Tambah Jadwal
</a>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Jadwal Peminjaman</div>
        <div class="page-subtitle">Kelola semua jadwal peminjaman workspace dan peralatan</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Semua Jadwal</div>
        <span style="font-size:12px;color:var(--text-muted);">{{ $schedules->total() }} jadwal ditemukan</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Member</th>
                    <th>Inventaris</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $sched)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">{{ $schedules->firstItem() + $loop->index }}</td>
                    <td>
                        <div style="font-weight:500;">{{ $sched->user->name ?? '—' }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $sched->user->email ?? '' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $sched->inventory->name ?? '—' }}</div>
                        @if($sched->inventory)
                        <div style="font-size:12px;color:var(--text-muted);">{{ $sched->inventory->type }}</div>
                        @endif
                    </td>
                    <td style="font-size:13px;color:var(--text-secondary);">{{ $sched->start_time->format('d M Y') }}<br><span style="font-size:12px;color:var(--text-muted);">{{ $sched->start_time->format('H:i') }}</span></td>
                    <td style="font-size:13px;color:var(--text-secondary);">{{ $sched->end_time->format('d M Y') }}<br><span style="font-size:12px;color:var(--text-muted);">{{ $sched->end_time->format('H:i') }}</span></td>
                    <td>
                        @if($sched->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($sched->status === 'booked')
                            <span class="badge badge-success">Booked</span>
                        @elseif($sched->status === 'checked_in')
                            <span class="badge badge-info">Checked In</span>
                        @elseif($sched->status === 'completed')
                            <span class="badge badge-success">Completed</span>
                        @else
                            <span class="badge badge-neutral">{{ ucfirst($sched->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            @if($sched->status === 'pending')
                                <form method="POST" action="{{ route('admin.schedules.approve', $sched) }}" onsubmit="return confirm('Setujui peminjaman ini?')" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--success);" title="Setujui">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.schedules.reject', $sched) }}" onsubmit="return confirm('Tolak peminjaman ini?')" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);" title="Tolak">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.schedules.show', $sched) }}" class="btn btn-ghost btn-sm" title="Detail">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.schedules.edit', $sched) }}" class="btn btn-ghost btn-sm" title="Edit">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.schedules.destroy', $sched) }}" onsubmit="return confirm('Hapus jadwal ini?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);" title="Hapus">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3>Belum ada jadwal</h3>
                            <p>Buat jadwal peminjaman pertama untuk member</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($schedules->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">Menampilkan {{ $schedules->firstItem() }}–{{ $schedules->lastItem() }} dari {{ $schedules->total() }}</div>
        <div class="pagination">{{ $schedules->links() }}</div>
    </div>
    @endif
</div>
@endsection
