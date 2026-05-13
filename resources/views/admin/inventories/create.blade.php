@extends('layouts.app')

@section('title', 'Tambah Inventaris')
@section('breadcrumb', 'Inventaris / Tambah')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Tambah Inventaris</div>
        <div class="page-subtitle">Daftarkan workspace atau peralatan baru ke sistem</div>
    </div>
    <a href="{{ route('admin.inventories.index') }}" class="btn btn-secondary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
</div>

<div style="max-width:600px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Detail Inventaris</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inventories.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Inventaris <span style="color:var(--danger)">*</span></label>
                        <input id="name" type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" placeholder="cth. Studio Room A, Kamera Sony A7III">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="type">Tipe <span style="color:var(--danger)">*</span></label>
                            <select id="type" name="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                                <option value="">— Pilih tipe —</option>
                                <option value="workspace" {{ old('type') === 'workspace' ? 'selected' : '' }}>Workspace</option>
                                <option value="equipment" {{ old('type') === 'equipment' ? 'selected' : '' }}>Equipment</option>
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="status">Status <span style="color:var(--danger)">*</span></label>
                            <select id="status" name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                <option value="available" {{ old('status', 'available') === 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="borrowed" {{ old('status') === 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" placeholder="Deskripsi singkat tentang inventaris ini...">{{ old('description') }}</textarea>
                        <div class="form-hint">Opsional — tambahkan spesifikasi, lokasi, atau catatan penting.</div>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display:flex;gap:8px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
                    <button type="submit" class="btn btn-primary">Simpan Inventaris</button>
                    <a href="{{ route('admin.inventories.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
