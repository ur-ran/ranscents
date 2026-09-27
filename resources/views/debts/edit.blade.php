@extends('layouts.app')

@section('title', 'Edit Utang/Piutang')
@section('page-title', 'Edit Utang/Piutang')
@section('page-subtitle', 'Update debt/receivable data')

@section('topbar-actions')
  <a href="{{ route('debts.index') }}" class="btn btn-outline">← Kembali</a>
@endsection

@section('content')
<div style="max-width: 760px;">
  <div class="card">
    <form action="{{ route('debts.update', $debt) }}" method="POST">
      @csrf @method('PUT')
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label"> Tipe *</label>
          <select name="type" class="form-control">
            <option value="owe" {{ $debt->type == 'owe' ? 'selected' : '' }}> Saya Berhutang</option>
            <option value="lend" {{ $debt->type == 'lend' ? 'selected' : '' }}> Saya Meminjamkan</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label"> Status *</label>
          <select name="status" class="form-control">
            <option value="active" {{ $debt->status == 'active' ? 'selected' : '' }}> Aktif</option>
            <option value="partially_paid" {{ $debt->status == 'partially_paid' ? 'selected' : '' }}> Sebagian Terbayar</option>
            <option value="paid" {{ $debt->status == 'paid' ? 'selected' : '' }}> Lunas</option>
          </select>
        </div>

        <div class="form-group full">
          <label class="form-label"> Nama Orang *</label>
          <input type="text" name="person_name" value="{{ old('person_name', $debt->person_name) }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label"> Jumlah Total (Rp) *</label>
          <input type="number" name="amount" value="{{ old('amount', $debt->amount) }}" class="form-control" min="0"  required>
        </div>

        <div class="form-group">
          <label class="form-label"> Sudah Dibayar (Rp)</label>
          <input type="number" name="paid_amount" value="{{ old('paid_amount', $debt->paid_amount) }}" class="form-control" min="0" >
        </div>

        <div class="form-group">
          <label class="form-label"> Tanggal Pinjam *</label>
          <input type="date" name="date" value="{{ old('date', $debt->date->format('Y-m-d')) }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label">⏰ Jatuh Tempo</label>
          <input type="date" name="due_date" value="{{ old('due_date', $debt->due_date?->format('Y-m-d')) }}" class="form-control">
        </div>

        <div class="form-group">
          <label class="form-label"> Kontak</label>
          <input type="text" name="contact" value="{{ old('contact', $debt->contact) }}" class="form-control">
        </div>

        <div class="form-group full">
          <label class="form-label"> Keterangan</label>
          <textarea name="description" class="form-control">{{ old('description', $debt->description) }}</textarea>
        </div>
      </div>

      <div class="divider"></div>
      <div style="display:flex; gap:12px; justify-content:flex-end;">
        <a href="{{ route('debts.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-gold"> Perbarui</button>
      </div>
    </form>
  </div>
</div>
@endsection
