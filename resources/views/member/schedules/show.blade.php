@extends('layouts.member')

@section('title', 'Detail Jadwal')
@section('breadcrumb', 'Jadwal Saya / Detail')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Detail Jadwal Peminjaman</div>
        <div class="page-subtitle">Informasi lengkap mengenai jadwal peminjaman Anda</div>
    </div>
    <a href="{{ route('member.schedules.index') }}" class="btn btn-secondary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <div class="card-title">Informasi Jadwal #{{ $schedule->id }}</div>
        <div>
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
        </div>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <div class="label">Inventaris</div>
                <div class="value" style="font-weight: 500;">
                    {{ $schedule->inventory->name ?? 'Inventaris Dihapus' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="label">Tipe Inventaris</div>
                <div class="value">
                    {{ $schedule->inventory ? ($schedule->inventory->type === 'workspace' ? 'Workspace' : 'Equipment') : '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="label">Waktu Mulai</div>
                <div class="value">
                    {{ $schedule->start_time->format('d F Y, H:i') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="label">Waktu Selesai</div>
                <div class="value">
                    {{ $schedule->end_time->format('d F Y, H:i') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="label">Tanggal Dibuat</div>
                <div class="value">
                    {{ $schedule->created_at->format('d M Y, H:i') }}
                </div>
            </div>
            
            <div class="detail-item">
                <div class="label">Status Saat Ini</div>
                <div class="value">
                    {{ ucfirst($schedule->status) }}
                </div>
            </div>
        </div>
        
        <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border);">
            <div style="display: flex; flex-direction: column; gap: 12px; align-items: flex-start;">
                <h4 style="font-size: 14px; font-weight: 600; color: var(--text-primary);">Aksi</h4>
                
                @if($schedule->status === 'pending')
                    <p style="font-size: 13px; color: var(--warning); font-weight: 500;">Jadwal masih menunggu konfirmasi dari admin.</p>
                @elseif($schedule->status === 'booked')
                    <p style="font-size: 13px; color: var(--text-secondary);">Silakan lakukan check-in saat Anda sudah berada di lokasi atau menerima peralatan.</p>
                    <form action="{{ route('member.schedules.check-in', $schedule) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin melakukan check-in sekarang?')">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Check-in Sekarang
                        </button>
                    </form>
                @elseif($schedule->status === 'checked_in')
                    <p style="font-size: 13px; color: var(--success); font-weight: 500; margin-bottom: 8px;">Anda telah melakukan check-in. Selamat menggunakan fasilitas!</p>
                    <p style="font-size: 13px; color: var(--text-secondary);">Silakan lakukan check-out saat Anda telah selesai menggunakan fasilitas atau mengembalikan peralatan.</p>
                    <form action="{{ route('member.schedules.check-out', $schedule) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary" onclick="return confirm('Apakah Anda yakin ingin melakukan check-out sekarang? (Ini akan menyelesaikan jadwal)')">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Check-out (Selesai)
                        </button>
                    </form>
                @elseif($schedule->status === 'completed')
                    <p style="font-size: 13px; color: var(--success); font-weight: 500;">Peminjaman ini telah selesai. Terima kasih!</p>
                @else
                    <p style="font-size: 13px; color: var(--text-muted);">Tidak ada aksi yang tersedia untuk status ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
