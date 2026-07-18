@extends('layouts.member')

@section('title', 'Pinjam Inventaris')
@section('breadcrumb', 'Jadwal Saya / Pinjam Inventaris')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Buat Peminjaman Baru</div>
        <div class="page-subtitle">Pilih inventaris dan tentukan jadwal peminjaman Anda</div>
    </div>
    <a href="{{ route('member.schedules.index') }}" class="btn btn-secondary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Batal
    </a>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <div class="card-title">Form Peminjaman</div>
    </div>
    <div class="card-body">
        <form action="{{ route('member.schedules.store') }}" method="POST">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="inventory_id">Pilih Inventaris <span style="color:var(--danger)">*</span></label>
                    <select name="inventory_id" id="inventory_id" class="form-control" required>
                        <option value="">-- Pilih Inventaris yang Tersedia --</option>
                        @foreach($inventories as $inv)
                            <option value="{{ $inv->id }}" {{ (old('inventory_id') ?? request('inventory_id')) == $inv->id ? 'selected' : '' }}>
                                {{ $inv->name }} ({{ $inv->type === 'workspace' ? 'Workspace' : 'Equipment' }})
                            </option>
                        @endforeach
                    </select>
                    @error('inventory_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="start_time">Waktu Mulai <span style="color:var(--danger)">*</span></label>
                    <div class="form-hint">Kapan Anda akan mulai menggunakan inventaris ini?</div>
                    <input type="datetime-local" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}" required>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="end_time">Waktu Selesai <span style="color:var(--danger)">*</span></label>
                    <div class="form-hint">Kapan Anda akan selesai menggunakan dan mengembalikan inventaris ini?</div>
                    <input type="datetime-local" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}" required>
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="background-color: var(--accent); border-color: var(--accent);">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Ajukan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
