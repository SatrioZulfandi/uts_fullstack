@extends('layouts.app')

@section('title', 'Inventaris')
@section('breadcrumb', 'Inventaris')

@section('topbar-actions')
<a href="{{ route('admin.inventories.create') }}" class="btn btn-primary">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
    </svg>
    Tambah Inventaris
</a>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Inventaris</div>
        <div class="page-subtitle">Kelola workspace dan peralatan studio</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Semua Inventaris</div>
        <span style="font-size:12px;color:var(--text-muted);">{{ $inventories->total() }} item ditemukan</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $inv)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">{{ $inventories->firstItem() + $loop->index }}</td>
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
                    <td style="color:var(--text-secondary);max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $inv->description ?? '—' }}
                    </td>
                    <td style="color:var(--text-muted);font-size:12.5px;">{{ $inv->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('admin.inventories.show', $inv) }}" class="btn btn-ghost btn-sm" title="Detail">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.inventories.edit', $inv) }}" class="btn btn-ghost btn-sm" title="Edit">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.inventories.destroy', $inv) }}" onsubmit="return confirm('Hapus inventaris ini?')">
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                            <h3>Belum ada inventaris</h3>
                            <p>Mulai tambahkan workspace atau peralatan studio</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($inventories->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">Menampilkan {{ $inventories->firstItem() }}–{{ $inventories->lastItem() }} dari {{ $inventories->total() }}</div>
        <div class="pagination">{{ $inventories->links() }}</div>
    </div>
    @endif
</div>
@endsection
