@extends('layouts.app')

@section('title', 'Edit Tabungan')
@section('page-title', 'Edit Tabungan')
@section('page-subtitle', 'Ubah data tabungan Anda')

@section('content')

<div class="card" style="max-width: 600px; margin: 0 auto;">
  <form action="{{ route('savings.update', $saving) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label class="form-label">Nama Tabungan <span style="color:var(--danger)">*</span></label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $saving->name) }}" required>
      @error('name') <div class="form-hint" style="color:var(--danger); margin-top:4px;">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label class="form-label">Jumlah (Rp) <span style="color:var(--danger)">*</span></label>
      <input type="number" name="amount" class="form-control" value="{{ old('amount', $saving->amount) }}" min="0" required>
      @error('amount') <div class="form-hint" style="color:var(--danger); margin-top:4px;">{{ $message }}</div> @enderror
      <div class="form-hint" style="margin-top:6px; color:var(--text-300);">
        ⚠️ Catatan: Mengubah jumlah di sini tidak akan otomatis mengubah data Pengeluaran yang terkait. Silakan edit Pengeluaran secara manual jika diperlukan.
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Tanggal <span style="color:var(--danger)">*</span></label>
      <input type="date" name="date" class="form-control" value="{{ old('date', $saving->date->format('Y-m-d')) }}" required>
      @error('date') <div class="form-hint" style="color:var(--danger); margin-top:4px;">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label class="form-label">Keterangan Tambahan</label>
      <textarea name="description" class="form-control">{{ old('description', $saving->description) }}</textarea>
      @error('description') <div class="form-hint" style="color:var(--danger); margin-top:4px;">{{ $message }}</div> @enderror
    </div>

    <div style="display: flex; gap: 10px; margin-top: 24px;">
      <a href="{{ route('savings.index') }}" class="btn btn-outline" style="flex: 1; text-align: center;">Batal</a>
      <button type="submit" class="btn btn-gold" style="flex: 1;">Simpan Perubahan</button>
    </div>
  </form>
</div>

@endsection
