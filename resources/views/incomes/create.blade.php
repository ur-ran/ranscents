@extends('layouts.app')

@section('title', 'Tambah Pemasukan')
@section('page-title', 'Tambah Pemasukan')
@section('page-subtitle', 'Record new income source')

@section('topbar-actions')
  <a href="{{ route('incomes.index') }}" class="btn btn-outline">← Kembali</a>
@endsection

@section('content')
<div style="max-width: 760px;">
  <div class="card">
    <form action="{{ route('incomes.store') }}" method="POST">
      @csrf
      <div class="form-grid">
        <div class="form-group full">
          <label class="form-label"> Judul / Keterangan *</label>
          <input type="text" name="title" value="{{ old('title') }}" class="form-control"
              placeholder="Contoh: Gaji bulan September" required>
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
          <label class="form-label"> Sumber</label>
          <input type="text" name="source" value="{{ old('source') }}" class="form-control"
              placeholder="Gaji, Freelance, Investasi, dll"
              list="source-suggestions">
          <datalist id="source-suggestions">
            <option value="Gaji">
            <option value="Freelance">
            <option value="Investasi">
            <option value="Bisnis">
            <option value="Bonus">
            <option value="Hadiah">
            <option value="Lainnya">
          </datalist>
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
          <label class="form-label">Pemasukan Rutin?</label>
          <select name="is_recurring" class="form-control" id="recurring-select" onchange="toggleRecurrence()">
            <option value="0" {{ old('is_recurring') == '0' ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ old('is_recurring') == '1' ? 'selected' : '' }}>Ya</option>
          </select>
        </div>

        <div class="form-group" id="recurrence-group" style="display:{{ old('is_recurring') == '1' ? 'flex' : 'none' }};">
          <label class="form-label">Frekuensi</label>
          <select name="recurrence" class="form-control">
            <option value="">Pilih...</option>
            <option value="daily" {{ old('recurrence') == 'daily' ? 'selected' : '' }}>Harian</option>
            <option value="weekly" {{ old('recurrence') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
            <option value="monthly" {{ old('recurrence') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
            <option value="yearly" {{ old('recurrence') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
          </select>
        </div>

        <div class="form-group full">
          <label class="form-label"> Catatan</label>
          <textarea name="notes" class="form-control" placeholder="Tambahkan catatan...">{{ old('notes') }}</textarea>
        </div>
      </div>

      <div class="divider"></div>
      <div style="display:flex; gap:12px; justify-content:flex-end;">
        <a href="{{ route('incomes.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-gold"> Simpan Pemasukan</button>
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
