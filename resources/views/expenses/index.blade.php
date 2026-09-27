@extends('layouts.app')

@section('title', 'Pengeluaran')
@section('page-title', 'Pengeluaran')
@section('page-subtitle', 'Manage all your expenses')

@section('topbar-actions')
  <a href="{{ route('expenses.create') }}" class="btn btn-gold">
    <span>+</span> Tambah Pengeluaran
  </a>
@endsection

@section('content')

<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 24px;">
  <div class="stat-card red">
    <div class="stat-icon red"></div>
    <div class="stat-label">Total Bulan Ini</div>
    <div class="stat-value red">Rp {{ number_format($monthlyTotal, 0, ',', '.') }}</div>
    <div class="stat-sub">{{ now()->format('F Y') }}</div>
  </div>
  <div class="stat-card gold">
    <div class="stat-icon gold"></div>
    <div class="stat-label">Total Filter Aktif</div>
    <div class="stat-value gold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
    <div class="stat-sub">{{ $expenses->total() }} transaksi</div>
  </div>
  <div class="stat-card blue">
    <div class="stat-icon blue"></div>
    <div class="stat-label">Rata-rata</div>
    <div class="stat-value blue">
      Rp {{ $expenses->total() > 0 ? number_format($totalAmount / $expenses->total(), 0, ',', '.') : '0' }}
    </div>
    <div class="stat-sub">per transaksi</div>
  </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom: 20px; padding: 20px;">
  <form action="{{ route('expenses.index') }}" method="GET">
    <div class="filter-bar">
      <div class="form-group">
        <label class="form-label"> Bulan</label>
        <input type="month" name="month" value="{{ request('month') }}" class="form-control">
      </div>
      <div class="form-group">
        <label class="form-label">Kategori</label>
        <select name="category" class="form-control">
          <option value="">Semua</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Metode</label>
        <select name="payment_method" class="form-control">
          <option value="">Semua</option>
          <option value="cash" {{ request('payment_method')=='cash' ? 'selected' : '' }}>Cash</option>
          <option value="transfer" {{ request('payment_method')=='transfer' ? 'selected' : '' }}>Transfer</option>
          <option value="card" {{ request('payment_method')=='card' ? 'selected' : '' }}>Kartu</option>
          <option value="ewallet" {{ request('payment_method')=='ewallet' ? 'selected' : '' }}>E-Wallet</option>
          <option value="qris" {{ request('payment_method')=='qris' ? 'selected' : '' }}>QRIS</option>
        </select>
      </div>
      <div style="display:flex; gap:8px; align-items:flex-end;">
        <button type="submit" class="btn btn-gold"> Filter</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-outline">Reset</a>
      </div>
    </div>
  </form>
</div>

{{-- Table --}}
<div class="card">
  @if($expenses->count() > 0)
    <div class="table-wrapper">
      <div class="table-responsive">
        <table>
        <thead>
          <tr>
            <th>Keterangan</th>
            <th>Kategori</th>
            <th>Metode</th>
            <th>Tanggal</th>
            <th>Rutin?</th>
            <th>Jumlah</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($expenses as $expense)
          <tr>
            <td>
              <div style="font-weight:600;color:var(--text-100);">{{ $expense->title }}</div>
              @if($expense->notes)
                <div style="font-size:11px;color:var(--text-300);">{{ Str::limit($expense->notes, 50) }}</div>
              @endif
            </td>
            <td>
              @if($expense->category)
                <span class="badge badge-gold">{{ $expense->category->name }}</span>
              @else
                <span style="color:var(--text-400);">—</span>
              @endif
            </td>
            <td>
              @php
                $methods = ['cash'=>' Cash','transfer'=>' Transfer','card'=>' Kartu','ewallet'=>' E-Wallet'];
              @endphp
              <span class="badge badge-gray">{{ $methods[$expense->payment_method] ?? $expense->payment_method }}</span>
            </td>
            <td>{{ $expense->date->format('d M Y') }}</td>
            <td>
              @if($expense->is_recurring)
                <span class="badge badge-blue"> {{ ucfirst($expense->recurrence) }}</span>
              @else
                <span style="color:var(--text-400);">—</span>
              @endif
            </td>
            <td class="amount-negative" style="font-size:15px;">
              -Rp {{ number_format($expense->amount, 0, ',', '.') }}
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-outline btn-sm">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>
                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Hapus pengeluaran ini?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
        </table>
      </div>
    </div>
    <div class="pagination">
      {{ $expenses->links('vendor.pagination.custom') }}
    </div>
  @else
    <div class="empty-state">
      <div class="empty-icon"></div>
      <h3>Belum ada pengeluaran</h3>
      <p>Mulai catat pengeluaran Anda</p>
      <a href="{{ route('expenses.create') }}" class="btn btn-gold">+ Tambah Pengeluaran</a>
    </div>
  @endif
</div>
@endsection
