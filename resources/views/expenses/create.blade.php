@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')
@section('page-title', 'Tambah Pengeluaran')
@section('page-subtitle', 'Record new expense')

@section('topbar-actions')
  <a href="{{ route('expenses.index') }}" class="btn btn-outline">← Kembali</a>
@endsection

@section('content')
<div style="max-width: 760px;">
  <div class="card">
    <form action="{{ route('expenses.store') }}" method="POST">
      @csrf
      <div class="form-grid">
        <div class="form-group full">
          <label class="form-label"> Keterangan *</label>
          <input type="text" name="title" value="{{ old('title') }}" class="form-control"
              placeholder="Contoh: Makan siang, Bensin, Token listrik" required>
        </div>

        <div class="form-group">
          <label class="form-label"> Jumlah (Rp) *</label>
          <input type="number" name="amount" value="{{ old('amount') }}" class="form-control"
              placeholder="0" min="0"  required>
        </div>

        <div class="form-group">
          <label class="form-label"> Tanggal *</label>
          <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label">Kategori</label>
          <select name="category_id" class="form-control">
            <option value="">Pilih Kategori</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Metode Pembayaran *</label>
          <select name="payment_method" class="form-control" required>
            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Kartu Kredit/Debit</option>
            <option value="ewallet" {{ old('payment_method') == 'ewallet' ? 'selected' : '' }}>E-Wallet (GoPay, OVO, dll)</option>
            <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Pengeluaran Rutin?</label>
          <select name="is_recurring" class="form-control" id="recurring-select" onchange="toggleRecurrence()">
            <option value="0">Tidak</option>
            <option value="1" {{ old('is_recurring') == '1' ? 'selected' : '' }}>Ya</option>
          </select>
        </div>

        <div class="form-group" id="recurrence-group" style="display:{{ old('is_recurring') == '1' ? 'flex' : 'none' }};">
          <label class="form-label">Frekuensi</label>
          <select name="recurrence" class="form-control">
            <option value="daily">Harian</option>
            <option value="weekly">Mingguan</option>
            <option value="monthly" selected>Bulanan</option>
            <option value="yearly">Tahunan</option>
          </select>
        </div>

        <div class="form-group full">
          <label class="form-label"> Catatan</label>
          <textarea name="notes" class="form-control" placeholder="Tambahkan catatan...">{{ old('notes') }}</textarea>
        </div>
      </div>

      <div class="divider"></div>
      <div style="display:flex; gap:12px; justify-content:flex-end;">
        <a href="{{ route('expenses.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-gold"> Simpan Pengeluaran</button>
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
