@extends('layouts.app')

@section('title', 'Edit Wishlist')
@section('page-title', 'Edit Item Wishlist')
@section('page-subtitle', 'Update item information')

@section('topbar-actions')
  <a href="{{ route('wishlists.index') }}" class="btn btn-outline">← Kembali</a>
@endsection

@section('styles')
<style>
.priority-options {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
  gap: 10px;
}

.priority-option {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  padding: 14px;
  border-radius: var(--radius);
  border: 2px solid var(--border);
  background: var(--bg-600);
  cursor: pointer;
  transition: var(--transition);
  text-align: center;
}

.priority-option input[type="radio"] { display: none; }
.priority-option .p-icon { font-size: 24px; }
.priority-option .p-label { font-size: 12px; font-weight: 600; }
.priority-option .p-desc { font-size: 10px; color: var(--text-400); }

.priority-option.selected-urgent  { border-color: #EF4444; background: rgba(239,68,68,0.08); }
.priority-option.selected-important { border-color: #F59E0B; background: rgba(245,158,11,0.08); }
.priority-option.selected-need   { border-color: #3B82F6; background: rgba(59,130,246,0.08); }
.priority-option.selected-want   { border-color: #8B5CF6; background: rgba(139,92,246,0.08); }
.priority-option.selected-low   { border-color: #6B7280; background: rgba(107,114,128,0.08); }
</style>
@endsection

@section('content')
<div style="max-width: 760px;">
  <div class="card">
    <form action="{{ route('wishlists.update', $wishlist) }}" method="POST">
      @csrf @method('PUT')
      <div class="form-grid">
        <div class="form-group full">
          <label class="form-label"> Nama Barang / Item *</label>
          <input type="text" name="name" value="{{ old('name', $wishlist->name) }}" class="form-control" required>
        </div>

        <div class="form-group full">
          <label class="form-label"> Prioritas *</label>
          <div class="priority-options">
            @php
              $priorities = [
                'urgent'  => ['', 'Mendesak', 'Harus beli sekarang!'],
                'important' => ['', 'Penting', 'Perlu dalam waktu dekat'],
                'need'   => ['', 'Butuh', 'Diperlukan tapi bisa nanti'],
                'want'   => ['', 'Ingin', 'Mau tapi tidak mendesak'],
                'low'    => ['', 'Tidak Penting', 'Kalau ada rezeki'],
              ];
            @endphp
            @foreach($priorities as $value => [$icon, $label, $desc])
              <label class="priority-option {{ $wishlist->priority == $value ? 'selected-'.$value : '' }}"
                  id="opt-{{ $value }}"
                  onclick="selectPriority('{{ $value }}')">
                <input type="radio" name="priority" value="{{ $value }}"
                    {{ old('priority', $wishlist->priority) == $value ? 'checked' : '' }}>
                <span class="p-icon">{{ $icon }}</span>
                <span class="p-label">{{ $label }}</span>
                <span class="p-desc">{{ $desc }}</span>
              </label>
            @endforeach
          </div>
        </div>

        <div class="form-group">
          <label class="form-label"> Estimasi Harga (Rp)</label>
          <input type="number" name="estimated_price" value="{{ old('estimated_price', $wishlist->estimated_price) }}"
              class="form-control" min="0" >
        </div>

        <div class="form-group">
          <label class="form-label"> Sudah Ditabung (Rp)</label>
          <input type="number" name="saved_amount" value="{{ old('saved_amount', $wishlist->saved_amount) }}"
              class="form-control" min="0" >
        </div>

        <div class="form-group">
          <label class="form-label">️ Kategori Barang</label>
          <input type="text" name="category" value="{{ old('category', $wishlist->category) }}"
              class="form-control" placeholder="Elektronik, Fashion, Otomotif...">
        </div>

        <div class="form-group">
          <label class="form-label"> Nama Toko</label>
          <input type="text" name="store" value="{{ old('store', $wishlist->store) }}" class="form-control">
        </div>

        <div class="form-group">
          <label class="form-label"> Target Beli</label>
          <input type="date" name="target_date"
              value="{{ old('target_date', $wishlist->target_date?->format('Y-m-d')) }}"
              class="form-control">
        </div>

        <div class="form-group">
          <label class="form-label"> Sudah Dibeli?</label>
          <select name="is_purchased" class="form-control">
            <option value="0" {{ !$wishlist->is_purchased ? 'selected' : '' }}>Belum</option>
            <option value="1" {{ $wishlist->is_purchased ? 'selected' : '' }}>Sudah Dibeli </option>
          </select>
        </div>

        <div class="form-group full">
          <label class="form-label"> Link Produk</label>
          <input type="url" name="url" value="{{ old('url', $wishlist->url) }}" class="form-control">
        </div>

        <div class="form-group full">
          <label class="form-label"> Catatan</label>
          <textarea name="notes" class="form-control">{{ old('notes', $wishlist->notes) }}</textarea>
        </div>
      </div>

      <div class="divider"></div>
      <div style="display:flex; gap:12px; justify-content:flex-end;">
        <a href="{{ route('wishlists.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-gold"> Perbarui</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
const priorityColors = {
  urgent: 'selected-urgent',
  important: 'selected-important',
  need: 'selected-need',
  want: 'selected-want',
  low: 'selected-low',
};

function selectPriority(value) {
  document.querySelectorAll('.priority-option').forEach(el => {
    el.className = 'priority-option';
  });
  const opt = document.getElementById('opt-' + value);
  opt.classList.add(priorityColors[value]);
  opt.querySelector('input').checked = true;
}
</script>
@endsection
