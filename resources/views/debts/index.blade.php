@extends('layouts.app')

@section('title', 'Utang & Piutang')
@section('page-title', 'Utang & Piutang')
@section('page-subtitle', 'Monitor all your debts and receivables')

@section('topbar-actions')
  <a href="{{ route('debts.create') }}" class="btn btn-gold">+ Tambah Data</a>
@endsection

@section('styles')
<style>
  /* Desktop: side by side */
  .debt-card-inner {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
  }

  .debt-card-left {
    flex: 1;
    min-width: 0;
  }

  .debt-card-right {
    text-align: right;
    flex-shrink: 0;
    min-width: 160px;
  }

  .debt-actions {
    display: flex;
    gap: 6px;
    margin-top: 12px;
    justify-content: flex-end;
    flex-wrap: wrap;
  }

  .debt-progress {
    margin-top: 12px;
  }

  /* Mobile: compact card */
  @media (max-width: 640px) {
    .debt-card {
      padding: 10px 12px !important;
    }

    .debt-card-inner {
      flex-direction: column;
      gap: 0;
    }

    .debt-card-left {
      width: 100%;
    }

    /* Name */
    .debt-card-left > div:first-child > div > div:first-child {
      font-size: 13px !important;
      font-weight: 700;
    }
    /* Subtitle */
    .debt-card-left > div:first-child > div > div:last-child {
      font-size: 10px !important;
    }
    /* Description italic */
    .debt-card-left > div:nth-child(2) {
      font-size: 10px !important;
      margin-bottom: 4px !important;
    }
    /* Badges + date */
    .debt-card-left > div:nth-child(3) {
      gap: 4px !important;
      margin-bottom: 5px !important;
    }

    .debt-card-right {
      text-align: left;
      width: 100%;
      min-width: 0;
      border-top: 1px solid var(--border);
      padding-top: 8px;
      margin-top: 4px;
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
      gap: 6px;
    }

    .debt-amount-info > div:first-child {
      font-size: 14px !important;
    }

    .debt-amount-info > div:not(:first-child) {
      font-size: 10px !important;
    }

    .debt-actions {
      justify-content: flex-end;
      margin-top: 0;
      gap: 4px !important;
    }

    .debt-actions .btn-sm {
      padding: 3px 7px !important;
      font-size: 11px !important;
    }

    .debt-progress {
      margin-top: 5px;
    }

    .debt-progress > div:first-child {
      font-size: 10px !important;
      margin-bottom: 3px !important;
    }

    .debt-progress > div:last-child {
      height: 4px !important;
    }

    .filter-actions {
      width: 100%;
    }
  }
</style>
@endsection

@section('content')

{{-- Summary --}}
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 24px;">
  <div class="stat-card red">
    <div class="stat-icon red"></div>
    <div class="stat-label">Total Utang Saya</div>
    <div class="stat-value red">Rp {{ number_format($totalOwed, 0, ',', '.') }}</div>
    <div class="stat-sub">yang belum dibayar</div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon green"></div>
    <div class="stat-label">Piutang Saya</div>
    <div class="stat-value green">Rp {{ number_format($totalLent, 0, ',', '.') }}</div>
    <div class="stat-sub">yang belum dikembalikan</div>
  </div>
  <div class="stat-card {{ $overdueCount > 0 ? 'red' : 'gold' }}">
    <div class="stat-icon {{ $overdueCount > 0 ? 'red' : 'gold' }}">️</div>
    <div class="stat-label">Jatuh Tempo</div>
    <div class="stat-value {{ $overdueCount > 0 ? 'red' : 'gold' }}">{{ $overdueCount }}</div>
    <div class="stat-sub">{{ $overdueCount > 0 ? 'Harus segera diselesaikan!' : 'Semua aman ' }}</div>
  </div>
</div>

{{-- Filter --}}
<div class="card debt-filter-bar" style="margin-bottom: 16px; padding: 12px 16px;">
  <form action="{{ route('debts.index') }}" method="GET">
    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
      <select name="type" class="form-control" style="flex:1; min-width:120px; padding:7px 12px; font-size:13px;">
        <option value="">Semua Tipe</option>
        <option value="owe" {{ request('type') == 'owe' ? 'selected' : '' }}>Saya Berhutang</option>
        <option value="lend" {{ request('type') == 'lend' ? 'selected' : '' }}>Saya Meminjamkan</option>
      </select>
      <select name="status" class="form-control" style="flex:1; min-width:120px; padding:7px 12px; font-size:13px;">
        <option value="">Semua Status</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
        <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Sebagian</option>
        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
      </select>
      <div class="filter-actions" style="display:flex; gap:10px; flex-shrink:0; flex-wrap:nowrap;">
        <button type="submit" class="btn btn-gold btn-sm" style="flex:1;">Filter</button>
        <a href="{{ route('debts.index') }}" class="btn btn-outline btn-sm" style="flex:1; text-align:center; box-sizing:border-box;">Reset</a>
      </div>
    </div>
  </form>
</div>

{{-- Debt Cards --}}
<div class="card">
  @if($debts->count() > 0)
    <div style="display: grid; gap: 16px;">
      @foreach($debts as $debt)
      <div class="debt-card" style="
        background: var(--bg-600);
        border: 1px solid {{ $debt->is_overdue ? 'rgba(239,68,68,0.4)' : ($debt->type == 'owe' ? 'rgba(239,68,68,0.15)' : 'rgba(34,197,94,0.15)') }};
        border-left: 3px solid {{ $debt->type == 'owe' ? '#EF4444' : '#22C55E' }};
        border-radius: var(--radius);
        padding: 20px;
        transition: var(--transition);
        {{ $debt->is_overdue ? 'box-shadow: 0 0 20px rgba(239,68,68,0.1);' : '' }}
      " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">

        {{-- Card Inner: Left + Right --}}
        <div class="debt-card-inner">
          {{-- LEFT: Info --}}
          <div class="debt-card-left">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
              <div>
                <div style="font-weight:700; font-size:16px; color:var(--text-100);">
                  {{ $debt->person_name }}
                </div>
                <div style="font-size:12px; color:var(--text-300);">
                  {{ $debt->type == 'owe' ? 'Saya berhutang kepada' : 'Saya meminjamkan kepada' }}
                  @if($debt->contact) · {{ $debt->contact }} @endif
                </div>
              </div>
            </div>

            @if($debt->description)
              <div style="font-size:13px; color:var(--text-300); margin-bottom:8px; font-style:italic;">{{ $debt->description }}</div>
            @endif

            <div style="display:flex; flex-wrap:wrap; gap:6px; align-items:center; margin-bottom:10px;">
              @if($debt->status == 'paid')
                <span class="badge badge-green">Lunas</span>
              @elseif($debt->status == 'partially_paid')
                <span class="badge badge-orange">Sebagian</span>
              @else
                <span class="badge badge-red">Aktif</span>
              @endif
              @if($debt->is_overdue)
                <span class="badge badge-red" style="animation: pulse 2s infinite;">Jatuh Tempo!</span>
              @endif
              <span style="font-size:12px; color:var(--text-300);">{{ $debt->date->format('d M Y') }}</span>
              @if($debt->due_date)
                <span style="font-size:12px; color:{{ $debt->is_overdue ? 'var(--danger)' : 'var(--text-300)' }};">
                  · Tempo: {{ $debt->due_date->format('d M Y') }}
                </span>
              @endif
            </div>

            {{-- Progress Bar (full width of left col on desktop, full card on mobile) --}}
            @if($debt->paid_amount > 0 || $debt->status != 'active')
              <div class="debt-progress">
                <div style="display:flex; justify-content:space-between; font-size:11px; color:var(--text-400); margin-bottom:4px;">
                  <span>Progres</span>
                  <span style="font-weight:600;">{{ $debt->progress_percentage }}%</span>
                </div>
                <div style="height:5px; background:var(--bg-500); border-radius:3px; overflow:hidden;">
                  <div style="
                    height:100%; border-radius:3px;
                    width: {{ $debt->progress_percentage }}%;
                    background: linear-gradient(90deg, {{ $debt->type == 'owe' ? '#EF4444, #F97316' : '#22C55E, #86EFAC' }});
                    transition: width 0.6s ease;
                  "></div>
                </div>
              </div>
            @endif
          </div>

          {{-- RIGHT: Amount + Actions --}}
          <div class="debt-card-right">
            <div class="debt-amount-info">
              <div style="font-size:22px; font-weight:800; font-family:'Quicksand',serif;
                color:{{ $debt->type == 'owe' ? 'var(--danger)' : 'var(--success)' }}; white-space:nowrap;">
                Rp {{ number_format($debt->amount, 0, ',', '.') }}
              </div>
              @if($debt->paid_amount > 0)
                <div style="font-size:12px; color:var(--text-300); white-space:nowrap;">
                  Terbayar: Rp {{ number_format($debt->paid_amount, 0, ',', '.') }}
                </div>
                <div style="font-size:12px; color:{{ $debt->type == 'owe' ? 'var(--danger)' : 'var(--success)' }}; font-weight:600; white-space:nowrap;">
                  Sisa: Rp {{ number_format($debt->remaining_amount, 0, ',', '.') }}
                </div>
              @endif
            </div>

            <div class="debt-actions">
              @if($debt->status != 'paid')
                <button onclick="openPayModal({{ $debt->id }}, '{{ addslashes($debt->person_name) }}', {{ $debt->remaining_amount }}, '{{ $debt->type }}')"
                    class="btn btn-success btn-sm">
                  {{ $debt->type == 'owe' ? 'Bayar' : 'Terima' }}
                </button>
              @endif
              <a href="{{ route('debts.edit', $debt) }}" class="btn btn-outline btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </a>
              <form action="{{ route('debts.destroy', $debt) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="pagination">
      {{ $debts->links('vendor.pagination.custom') }}
    </div>
  @else
    <div class="empty-state">
      <div class="empty-icon"></div>
      <h3>Tidak ada data utang/piutang</h3>
      <p>Catat utang atau piutang Anda di sini</p>
      <a href="{{ route('debts.create') }}" class="btn btn-gold">+ Tambah Data</a>
    </div>
  @endif
</div>

{{-- Pay Modal --}}
<div class="modal-overlay" id="pay-modal">
  <div class="modal">
    <h3 class="modal-title" id="modal-title">Bayar Utang</h3>
    <p id="pay-modal-name" style="color:var(--text-200); margin-bottom:16px;"></p>
    <form id="pay-form" method="POST">
      @csrf
      <div class="form-group" style="margin-bottom:16px;">
        <label class="form-label" id="modal-amount-label">Jumlah Pembayaran (Rp)</label>
        <input type="number" name="pay_amount" id="pay-amount" class="form-control"
            placeholder="0" min="1"  required>
        <div class="form-hint" id="pay-remaining"></div>
      </div>
      <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button type="button" onclick="closePayModal()" class="btn btn-outline">Batal</button>
        <button type="submit" class="btn btn-gold" id="submit-btn">Konfirmasi Bayar</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
function openPayModal(id, name, remaining, type) {
  document.getElementById('pay-modal').classList.add('active');
  
  if (type === 'owe') {
    document.getElementById('modal-title').textContent = 'Bayar Utang';
    document.getElementById('pay-modal-name').textContent = 'Kepada: ' + name;
    document.getElementById('modal-amount-label').textContent = 'Jumlah Pembayaran (Rp)';
    document.getElementById('pay-remaining').textContent = 'Sisa utang: Rp ' + remaining.toLocaleString('id-ID');
    document.getElementById('submit-btn').textContent = 'Konfirmasi Bayar';
  } else {
    document.getElementById('modal-title').textContent = 'Terima Pembayaran Piutang';
    document.getElementById('pay-modal-name').textContent = 'Dari: ' + name;
    document.getElementById('modal-amount-label').textContent = 'Jumlah Diterima (Rp)';
    document.getElementById('pay-remaining').textContent = 'Sisa piutang: Rp ' + remaining.toLocaleString('id-ID');
    document.getElementById('submit-btn').textContent = 'Konfirmasi Terima';
  }

  document.getElementById('pay-amount').max = remaining;
  document.getElementById('pay-form').action = '/debts/' + id + '/pay';
}

function closePayModal() {
  document.getElementById('pay-modal').classList.remove('active');
}

document.getElementById('pay-modal').addEventListener('click', function(e) {
  if (e.target === this) closePayModal();
});
</script>
@endsection
