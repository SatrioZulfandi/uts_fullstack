@extends('layouts.app')

@section('title', 'Tambah Jadwal')
@section('breadcrumb', 'Jadwal / Tambah')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Tambah Jadwal Peminjaman</div>
        <div class="page-subtitle">Buat jadwal peminjaman baru untuk member</div>
    </div>
    <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
</div>

<div style="max-width:600px;">
    <div class="card">
        <div class="card-header"><div class="card-title">Detail Jadwal</div></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.schedules.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="user_id">Member <span style="color:var(--danger)">*</span></label>
                        <select id="user_id" name="user_id" class="form-control {{ $errors->has('user_id') ? 'is-invalid' : '' }}">
                            <option value="">— Pilih member —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="inventory_id">Inventaris <span style="color:var(--danger)">*</span></label>
                        <select id="inventory_id" name="inventory_id" class="form-control {{ $errors->has('inventory_id') ? 'is-invalid' : '' }}">
                            <option value="">— Pilih inventaris tersedia —</option>
                            @foreach($inventories as $inv)
                                <option value="{{ $inv->id }}" {{ old('inventory_id') == $inv->id ? 'selected' : '' }}>
                                    {{ $inv->name }} — {{ $inv->type }}
                                </option>
                            @endforeach
                        </select>
                        @error('inventory_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label" for="start_time">Waktu Mulai <span style="color:var(--danger)">*</span></label>
                            <input id="start_time" type="datetime-local" name="start_time" class="form-control {{ $errors->has('start_time') ? 'is-invalid' : '' }}" value="{{ old('start_time') }}">
                            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="end_time">Waktu Selesai <span style="color:var(--danger)">*</span></label>
                            <input id="end_time" type="datetime-local" name="end_time" class="form-control {{ $errors->has('end_time') ? 'is-invalid' : '' }}" value="{{ old('end_time') }}">
                            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status <span style="color:var(--danger)">*</span></label>
                        <select id="status" name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display:flex;gap:8px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
