@extends('layouts.app')

@section('title', 'Edit Pemasukan')
@section('page-title', 'Edit Pemasukan')
@section('page-subtitle', 'Update income data')

@section('topbar-actions')
  <a href="{{ route('incomes.index') }}" class="btn btn-outline">← Kembali</a>
@endsection

@section('content')
<div style="max-width: 760px;">
  <div class="card">
    <form action="{{ route('incomes.update', $income) }}" method="POST">
      @csrf @method('PUT')
      <div class="form-grid">
        <div class="form-group full">
          <label class="form-label"> Judul / Keterangan *</label>
          <input type="text" name="title" value="{{ old('title', $income->title) }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label"> Jumlah (Rp) *</label>
          <input type="number" name="amount" value="{{ old('amount', $income->amount) }}" class="form-control" min="0"  required>
        </div>

        <div class="form-group">
          <label class="form-label"> Tanggal *</label>
          <input type="date" name="date" value="{{ old('date', $income->date->format('Y-m-d')) }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label"> Sumber</label>
          <input type="text" name="source" value="{{ old('source', $income->source) }}" class="form-control" placeholder="Gaji, Freelance, dll">
        </div>

        <div class="form-group">
          <label class="form-label">Kategori</label>
          <select name="category_id" class="form-control">
            <option value="">Pilih Kategori</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', $income->category_id) == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Pemasukan Rutin?</label>
          <select name="is_recurring" class="form-control" id="recurring-select" onchange="toggleRecurrence()">
            <option value="0" {{ !$income->is_recurring ? 'selected' : '' }}>Tidak</option>
            <option value="1" {{ $income->is_recurring ? 'selected' : '' }}>Ya</option>
          </select>
        </div>

        <div class="form-group" id="recurrence-group" style="display:{{ $income->is_recurring ? 'flex' : 'none' }};">
          <label class="form-label">Frekuensi</label>
          <select name="recurrence" class="form-control">
            <option value="">Pilih...</option>
            <option value="daily" {{ $income->recurrence == 'daily' ? 'selected' : '' }}>Harian</option>
            <option value="weekly" {{ $income->recurrence == 'weekly' ? 'selected' : '' }}>Mingguan</option>
            <option value="monthly" {{ $income->recurrence == 'monthly' ? 'selected' : '' }}>Bulanan</option>
            <option value="yearly" {{ $income->recurrence == 'yearly' ? 'selected' : '' }}>Tahunan</option>
          </select>
        </div>

        <div class="form-group full">
          <label class="form-label"> Catatan</label>
          <textarea name="notes" class="form-control">{{ old('notes', $income->notes) }}</textarea>
        </div>
      </div>

      <div class="divider"></div>
      <div style="display:flex; gap:12px; justify-content:flex-end;">
        <a href="{{ route('incomes.index') }}" class="btn btn-outline">Batal</a>
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
