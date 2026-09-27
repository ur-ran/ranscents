@extends('layouts.app')

@section('title', 'Things to Buy')
@section('page-title', 'Things to Buy')
@section('page-subtitle', 'Plan your purchases wisely')

@section('topbar-actions')
  <a href="{{ route('wishlists.create') }}" class="btn btn-gold"> Tambah Item</a>
@endsection

@section('styles')
<style>
  .priority-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 24px;
  }

  .priority-tab {
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: 2px solid var(--border);
    background: transparent;
    color: var(--text-200);
    text-decoration: none;
    transition: var(--transition);
  }

  .priority-tab:hover {
    border-color: var(--gold-500);
    color: var(--gold-400);
  }

  .priority-tab.active {
    background: linear-gradient(135deg, var(--gold-500), var(--gold-700));
    color: #0A0A0F;
    border-color: transparent;
  }

  .wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
  }

  .wishlist-card {
    background: var(--bg-700);
    border-radius: var(--radius-lg);
    padding: 20px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
  }

  .wishlist-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.3);
  }

  .wishlist-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
  }

  .priority-urgent .wishlist-card::before { background: linear-gradient(90deg, #EF4444, #F97316); }
  .priority-important .wishlist-card::before { background: linear-gradient(90deg, #F59E0B, #FBBF24); }
  .priority-need .wishlist-card::before { background: linear-gradient(90deg, #3B82F6, #60A5FA); }
  .priority-want .wishlist-card::before { background: linear-gradient(90deg, #8B5CF6, #A78BFA); }
  .priority-low .wishlist-card::before { background: linear-gradient(90deg, #6B7280, #9CA3AF); }

  .priority-section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    margin-top: 8px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--border);
  }

  .priority-badge-large {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
  }

  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
  }

  .pulse { animation: pulse 2s infinite; }
</style>
@endsection

@section('content')

{{-- Summary Stats --}}
<div class="stats-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 24px;">
  <div class="stat-card purple">
    <div class="stat-icon purple">️</div>
    <div class="stat-label">Item Wishlist</div>
    <div class="stat-value purple">{{ $wishlists->count() }}</div>
    <div class="stat-sub">belum dibeli</div>
  </div>
  <div class="stat-card gold">
    <div class="stat-icon gold"></div>
    <div class="stat-label">Total Estimasi</div>
    <div class="stat-value gold" style="font-size:18px;">Rp {{ number_format($totalEstimated, 0, ',', '.') }}</div>
    <div class="stat-sub">butuh dana</div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon green"></div>
    <div class="stat-label">Sudah Ditabung</div>
    <div class="stat-value green" style="font-size:18px;">Rp {{ number_format($totalSaved, 0, ',', '.') }}</div>
    <div class="stat-sub">
      @if($totalEstimated > 0)
        {{ round(($totalSaved / $totalEstimated) * 100) }}% dari target
      @else
        —
      @endif
    </div>
  </div>
  <div class="stat-card blue">
    <div class="stat-icon blue"></div>
    <div class="stat-label">Sudah Dibeli</div>
    <div class="stat-value blue">{{ $purchasedCount }}</div>
    <div class="stat-sub">item tercapai </div>
  </div>
</div>

{{-- Priority Filter Tabs --}}
<div class="priority-tabs">
  <a href="{{ route('wishlists.index') }}" class="priority-tab {{ !request('priority') ? 'active' : '' }}">
     Semua ({{ $wishlists->count() }})
  </a>
  <a href="{{ route('wishlists.index', ['priority' => 'urgent']) }}"
    class="priority-tab {{ request('priority')=='urgent' ? 'active' : '' }}"
    style="{{ request('priority')=='urgent' ? '' : 'border-color:rgba(239,68,68,0.4);color:#EF4444;' }}">
    Mendesak ({{ $grouped->get('urgent', collect())->count() }})
  </a>
  <a href="{{ route('wishlists.index', ['priority' => 'important']) }}"
    class="priority-tab {{ request('priority')=='important' ? 'active' : '' }}"
    style="{{ request('priority')=='important' ? '' : 'border-color:rgba(245,158,11,0.4);color:#F59E0B;' }}">
    Penting ({{ $grouped->get('important', collect())->count() }})
  </a>
  <a href="{{ route('wishlists.index', ['priority' => 'need']) }}"
    class="priority-tab {{ request('priority')=='need' ? 'active' : '' }}"
    style="{{ request('priority')=='need' ? '' : 'border-color:rgba(59,130,246,0.4);color:#3B82F6;' }}">
    Butuh ({{ $grouped->get('need', collect())->count() }})
  </a>
  <a href="{{ route('wishlists.index', ['priority' => 'want']) }}"
    class="priority-tab {{ request('priority')=='want' ? 'active' : '' }}"
    style="{{ request('priority')=='want' ? '' : 'border-color:rgba(139,92,246,0.4);color:#8B5CF6;' }}">
    Ingin ({{ $grouped->get('want', collect())->count() }})
  </a>
  <a href="{{ route('wishlists.index', ['priority' => 'low']) }}"
    class="priority-tab {{ request('priority')=='low' ? 'active' : '' }}"
    style="{{ request('priority')=='low' ? '' : 'border-color:rgba(107,114,128,0.4);color:#9CA3AF;' }}">
    Tidak Penting ({{ $grouped->get('low', collect())->count() }})
  </a>
</div>

@if($wishlists->count() > 0)
  @php
    $priorityConfig = [
      'urgent'  => ['label' => ' Mendesak', 'color' => '#EF4444', 'badge' => 'badge-red'],
      'important' => ['label' => ' Penting', 'color' => '#F59E0B', 'badge' => 'badge-orange'],
      'need'   => ['label' => ' Butuh', 'color' => '#3B82F6', 'badge' => 'badge-blue'],
      'want'   => ['label' => ' Ingin', 'color' => '#8B5CF6', 'badge' => 'badge-purple'],
      'low'    => ['label' => ' Tidak Penting', 'color' => '#6B7280', 'badge' => 'badge-gray'],
    ];

    if(request('priority')) {
      $displayGroups = collect([request('priority') => $wishlists->groupBy('priority')->get(request('priority'), collect())]);
    } else {
      $displayGroups = $grouped;
    }
  @endphp

  @foreach($priorityConfig as $priority => $config)
    @if(isset($displayGroups[$priority]) && $displayGroups[$priority]->count() > 0)
    <div style="margin-bottom: 32px;" class="priority-{{ $priority }}">
      <div class="priority-section-header">
        <span class="badge {{ $config['badge'] }}" style="font-size:14px;padding:6px 14px;">
          {{ $config['label'] }}
        </span>
        <span style="font-size:13px;color:var(--text-300);">
          {{ $displayGroups[$priority]->count() }} item
        </span>
        @if($priority == 'urgent')
          <span class="badge badge-red pulse" style="margin-left:auto;">️ Perlu Perhatian!</span>
        @endif
      </div>

      <div class="wishlist-grid">
        @foreach($displayGroups[$priority] as $item)
        <div class="wishlist-card" style="border: 1px solid {{ $item->is_overdue ?? false ? 'rgba(239,68,68,0.3)' : 'rgba(212,175,55,0.1)' }};">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
            <div style="flex:1;">
              <h4 style="font-weight:700; color:var(--text-100); margin-bottom:4px; font-size:16px;">
                {{ $item->name }}
              </h4>
              @if($item->category)
                <span style="font-size:11px; color:var(--text-300);">️ {{ $item->category }}</span>
              @endif
            </div>
            <span class="badge {{ $config['badge'] }}">{{ $config['label'] }}</span>
          </div>

          @if($item->estimated_price)
          <div style="background:var(--bg-500); border-radius:10px; padding:14px; margin-bottom:14px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
              <span style="font-size:12px; color:var(--text-300);">Target Harga</span>
              <span style="font-weight:700; color:var(--gold-400);">
                Rp {{ number_format($item->estimated_price, 0, ',', '.') }}
              </span>
            </div>
            @if($item->saved_amount > 0)
              <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                <span style="font-size:12px; color:var(--text-300);">Sudah Ditabung</span>
                <span style="font-weight:600; color:var(--success);">
                  Rp {{ number_format($item->saved_amount, 0, ',', '.') }}
                </span>
              </div>
              <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $item->saving_progress }}%;"></div>
              </div>
              <div style="text-align:right; font-size:11px; color:var(--text-300); margin-top:4px;">
                {{ $item->saving_progress }}% tercapai
              </div>
            @endif
          </div>
          @endif

          <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:14px; font-size:12px; color:var(--text-300);">
            @if($item->store)
              <span> {{ $item->store }}</span>
            @endif
            @if($item->target_date)
              <span> Target: {{ $item->target_date->format('d M Y') }}</span>
            @endif
            @if($item->url)
              <a href="{{ $item->url }}" target="_blank" style="color:var(--gold-400);text-decoration:none;">
                 Lihat Produk
              </a>
            @endif
          </div>

          @if($item->notes)
            <div style="font-size:12px; color:var(--text-300); margin-bottom:12px; padding:8px; background:var(--bg-500); border-radius:8px;">
               {{ $item->notes }}
            </div>
          @endif

          <div style="display:flex; gap:6px; justify-content:flex-end; flex-wrap:wrap;">
            <form action="{{ route('wishlists.purchase', $item) }}" method="POST"
               onsubmit="return confirm('Tandai {{ addslashes($item->name) }} sebagai sudah dibeli?')">
              @csrf
              <button type="submit" class="btn btn-success btn-sm"> Sudah Beli</button>
            </form>
            <a href="{{ route('wishlists.edit', $item) }}" class="btn btn-outline btn-sm">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </a>
            <form action="{{ route('wishlists.destroy', $item) }}" method="POST"
               onsubmit="return confirm('Hapus item ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              </button>
            </form>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif
  @endforeach
@else
  <div class="card">
    <div class="empty-state">
      <div class="empty-icon">️</div>
      <h3>Wishlist Anda kosong</h3>
      <p>Tambahkan barang yang ingin Anda beli dan atur prioritasnya</p>
      <a href="{{ route('wishlists.create') }}" class="btn btn-gold"> Tambah Item Pertama</a>
    </div>
  </div>
@endif
@endsection
