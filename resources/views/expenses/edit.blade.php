@extends('layouts.app')

@section('title', 'Edit Pengeluaran')
@section('page-title', 'Edit Pengeluaran')
@section('page-subtitle', 'Update expense data')

@section('topbar-actions')
  <a href="{{ route('expenses.index') }}" class="btn btn-outline">← Kembali</a>
@endsection

@section('content')
<div style="max-width: 760px;">
  <div class="card">
    <form action="{{ route('expenses.update', $expense) }}" method="POST">
      @csrf @method('PUT')
      <div class="form-grid">
        <div class="form-group full">
          <label class="form-label"> Keterangan *</label>
          <input type="text" name="title" value="{{ old('title', $expense->title) }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label"> Jumlah (Rp) *</label>
          <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" class="form-control" min="0"  required>
        </div>

        <div class="form-group">
          <label class="form-label"> Tanggal *</label>
          <input type="date" name="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label">Kategori</label>
          <select name="category_id" class="form-control">
            <option value="">Pilih Kategori</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', $expense->category_id) == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Metode Pembayaran *</label>
          <select name="payment_method" class="form-control" required>
            <option value="cash" {{ $expense->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="transfer" {{ $expense->payment_method == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
            <option value="card" {{ $expense->payment_method == 'card' ? 'selected' : '' }}>Kartu Kredit/Debit</option>
            <option value="ewallet" {{ $expense->payment_method == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
            <option value="qris" {{ $expense->payment_method == 'qris' ? 'selected' : '' }}>QRIS</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Pengeluaran Rutin?</label>
          <select name="is_recurring" class="form-control" id="recurring-select" onchange="toggleRecurrence()">
            <option value="0" {{ !$expense->is_recurring ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ $expense->is_recurring ? 'selected' : '' }}>Ya</option>
          </select>
        </div>

        <div class="form-group" id="recurrence-group" style="display:{{ $expense->is_recurring ? 'flex' : 'none' }};">
          <label class="form-label">Frekuensi</label>
          <select name="recurrence" class="form-control">
            <option value="daily" {{ $expense->recurrence == 'daily' ? 'selected' : '' }}>Harian</option>
            <option value="weekly" {{ $expense->recurrence == 'weekly' ? 'selected' : '' }}>Mingguan</option>
            <option value="monthly" {{ $expense->recurrence == 'monthly' ? 'selected' : '' }}>Bulanan</option>
            <option value="yearly" {{ $expense->recurrence == 'yearly' ? 'selected' : '' }}>Tahunan</option>
          </select>
        </div>

        <div class="form-group full">
          <label class="form-label"> Catatan</label>
          <textarea name="notes" class="form-control">{{ old('notes', $expense->notes) }}</textarea>
        </div>
      </div>

      <div class="divider"></div>
      <div style="display:flex; gap:12px; justify-content:flex-end;">
        <a href="{{ route('expenses.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-gold"> Perbarui</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
function toggleRecurrence() {
  const val = document.getElementById('recurring-select').value;
  document.getElementById('recurrence-group').style.display = val == '1' ? 'flex' : 'none';
}
</script>
@endsection
