@extends('layouts.member')

@section('title', 'Inventaris Tersedia')
@section('breadcrumb', 'Inventaris Tersedia')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Inventaris Tersedia</div>
        <div class="page-subtitle">Daftar workspace dan peralatan yang siap digunakan</div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $inventory)
                <tr>
                    <td style="font-weight:500;">{{ $inventory->name }}</td>
                    <td>
                        <span class="badge {{ $inventory->type === 'workspace' ? 'badge-info' : 'badge-neutral' }}">
                            {{ $inventory->type === 'workspace' ? 'Workspace' : 'Equipment' }}
                        </span>
                    </td>
                    <td style="color:var(--text-secondary);max-width:300px;">{{ Str::limit($inventory->description, 80) }}</td>
                    <td>
                        <span class="badge badge-success">Tersedia</span>
                    </td>
                    <td>
                        <a href="{{ route('member.schedules.create', ['inventory_id' => $inventory->id]) }}" class="btn btn-primary btn-sm" style="background-color: var(--accent); border-color: var(--accent);">
                            Pinjam
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                            <h3>Belum ada inventaris tersedia</h3>
                            <p>Saat ini tidak ada workspace atau peralatan yang tersedia untuk dipinjam.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($inventories->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $inventories->firstItem() }}–{{ $inventories->lastItem() }} dari {{ $inventories->total() }}
        </div>
        <div class="pagination">
            {{ $inventories->links('pagination::simple-bootstrap-4') }}
        </div>
    </div>
    @endif
</div>
@endsection
