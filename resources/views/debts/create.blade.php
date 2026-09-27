@extends('layouts.app')

@section('title', 'Tambah Utang/Piutang')
@section('page-title', 'Tambah Utang/Piutang')
@section('page-subtitle', 'Record new debt or receivable')

@section('topbar-actions')
  <a href="{{ route('debts.index') }}" class="btn btn-outline">← Kembali</a>
@endsection

@section('content')
<div style="max-width: 760px;">
  <div class="card">
    <form action="{{ route('debts.store') }}" method="POST">
      @csrf
      <div class="form-grid">
        <div class="form-group full">
          <label class="form-label"> Tipe *</label>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <label style="
              display:flex; align-items:center; gap:12px;
              background: var(--bg-600); border: 2px solid var(--border);
              border-radius: var(--radius); padding: 16px; cursor:pointer;
              transition: var(--transition);"
              id="type-owe-label">
              <input type="radio" name="type" value="owe" {{ old('type','owe')=='owe'?'checked':'' }}
                  onchange="updateTypeStyle()" style="display:none;" id="type-owe">
              <span style="font-size:28px;"></span>
              <div>
                <div style="font-weight:700;color:var(--text-100);">Saya Berhutang</div>
                <div style="font-size:12px;color:var(--text-300);">Uang yang saya pinjam dari orang lain</div>
              </div>
            </label>
            <label style="
              display:flex; align-items:center; gap:12px;
              background: var(--bg-600); border: 2px solid var(--border);
              border-radius: var(--radius); padding: 16px; cursor:pointer;
              transition: var(--transition);"
              id="type-lend-label">
              <input type="radio" name="type" value="lend" {{ old('type')=='lend'?'checked':'' }}
                  onchange="updateTypeStyle()" style="display:none;" id="type-lend">
              <span style="font-size:28px;"></span>
              <div>
                <div style="font-weight:700;color:var(--text-100);">Saya Meminjamkan</div>
                <div style="font-size:12px;color:var(--text-300);">Uang yang saya pinjamkan ke orang lain</div>
              </div>
            </label>
          </div>
        </div>

        <div class="form-group full">
          <label class="form-label"> Nama Orang *</label>
          <input type="text" name="person_name" value="{{ old('person_name') }}" class="form-control"
              placeholder="Nama teman, keluarga, atau rekan" required>
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
          <label class="form-label">⏰ Jatuh Tempo</label>
          <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-control">
          <span class="form-hint">Kosongkan jika tidak ada batas waktu</span>
        </div>

        <div class="form-group">
          <label class="form-label"> Kontak</label>
          <input type="text" name="contact" value="{{ old('contact') }}" class="form-control"
              placeholder="No. HP, email, dll">
        </div>

        <div class="form-group full">
          <label class="form-label"> Keterangan</label>
          <textarea name="description" class="form-control"
               placeholder="Untuk keperluan apa utang ini...">{{ old('description') }}</textarea>
        </div>
      </div>

      <div class="divider"></div>
      <div style="display:flex; gap:12px; justify-content:flex-end;">
        <a href="{{ route('debts.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-gold"> Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
function updateTypeStyle() {
  const oweChecked = document.getElementById('type-owe').checked;
  const lendChecked = document.getElementById('type-lend').checked;

  document.getElementById('type-owe-label').style.borderColor = oweChecked ? 'var(--danger)' : 'var(--border)';
  document.getElementById('type-owe-label').style.background = oweChecked ? 'rgba(239,68,68,0.08)' : 'var(--bg-600)';
  document.getElementById('type-lend-label').style.borderColor = lendChecked ? 'var(--success)' : 'var(--border)';
  document.getElementById('type-lend-label').style.background = lendChecked ? 'rgba(34,197,94,0.08)' : 'var(--bg-600)';
}

// Initialize on load
document.querySelectorAll('[name="type"]').forEach(r => r.addEventListener('change', updateTypeStyle));
updateTypeStyle();

// Click on label triggers radio
document.getElementById('type-owe-label').addEventListener('click', function() {
  document.getElementById('type-owe').checked = true;
  updateTypeStyle();
});
document.getElementById('type-lend-label').addEventListener('click', function() {
  document.getElementById('type-lend').checked = true;
  updateTypeStyle();
});
</script>
@endsection
