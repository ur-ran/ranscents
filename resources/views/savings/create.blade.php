@extends('layouts.app')

@section('title', 'Tambah Tabungan')
@section('page-title', 'Tambah Tabungan')
@section('page-subtitle', 'Catat alokasi tabungan baru')

@section('content')

<div class="card" style="max-width: 600px; margin: 0 auto;">
  <form action="{{ route('savings.store') }}" method="POST">
    @csrf

    <div class="form-group">
      <label class="form-label">Nama Tabungan <span style="color:var(--danger)">*</span></label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Tabungan Darurat, Dana Liburan" required>
      @error('name') <div class="form-hint" style="color:var(--danger); margin-top:4px;">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label class="form-label">Jumlah (Rp) <span style="color:var(--danger)">*</span></label>
      <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" placeholder="Contoh: 500000" min="0" required>
      @error('amount') <div class="form-hint" style="color:var(--danger); margin-top:4px;">{{ $message }}</div> @enderror
      <div class="form-hint" style="margin-top:6px; color:var(--text-300);">
        💡 Menyimpan data ini otomatis akan mencatat Pengeluaran dengan kategori "Tabungan".
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Tanggal <span style="color:var(--danger)">*</span></label>
      <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
      @error('date') <div class="form-hint" style="color:var(--danger); margin-top:4px;">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label class="form-label">Keterangan Tambahan</label>
      <textarea name="description" class="form-control" placeholder="Catatan opsional...">{{ old('description') }}</textarea>
      @error('description') <div class="form-hint" style="color:var(--danger); margin-top:4px;">{{ $message }}</div> @enderror
    </div>

    <div style="display: flex; gap: 10px; margin-top: 24px;">
      <a href="{{ route('savings.index') }}" class="btn btn-outline" style="flex: 1; text-align: center;">Batal</a>
      <button type="submit" class="btn btn-gold" style="flex: 1;">Simpan</button>
    </div>
  </form>
</div>

@endsection
