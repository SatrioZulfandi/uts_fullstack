@extends('layouts.member')

@section('title', 'Jadwal Saya')
@section('breadcrumb', 'Jadwal Saya')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Jadwal Peminjaman</div>
        <div class="page-subtitle">Daftar jadwal peminjaman inventaris Anda</div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Inventaris</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                <tr>
                    <td style="font-weight:500;">
                        {{ $schedule->inventory->name ?? 'Inventaris Dihapus' }}
                        @if($schedule->inventory)
                            <div style="font-size:12px;color:var(--text-muted);font-weight:normal;margin-top:2px;">
                                {{ $schedule->inventory->type === 'workspace' ? 'Workspace' : 'Equipment' }}
                            </div>
                        @endif
                    </td>
                    <td style="color:var(--text-secondary);">
                        {{ $schedule->start_time->format('d M Y') }}
                        <div style="font-weight:500;color:var(--text-primary);">{{ $schedule->start_time->format('H:i') }}</div>
                    </td>
                    <td style="color:var(--text-secondary);">
                        {{ $schedule->end_time->format('d M Y') }}
                        <div style="font-weight:500;color:var(--text-primary);">{{ $schedule->end_time->format('H:i') }}</div>
                    </td>
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
                        <a href="{{ route('member.schedules.show', $schedule) }}" class="btn btn-secondary btn-sm">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3>Belum ada jadwal peminjaman</h3>
                            <p>Anda belum memiliki jadwal peminjaman apapun saat ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($schedules->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $schedules->firstItem() }}–{{ $schedules->lastItem() }} dari {{ $schedules->total() }}
        </div>
        <div class="pagination">
            {{ $schedules->links('pagination::simple-bootstrap-4') }}
        </div>
    </div>
    @endif
</div>
@endsection
