@extends('layouts.app')

@section('title', 'Pemasukan')
@section('page-title', 'Pemasukan')
@section('page-subtitle', 'Manage all your income sources')

@section('topbar-actions')
  <a href="{{ route('incomes.create') }}" class="btn btn-gold">
    <span>+</span> Tambah Pemasukan
  </a>
@endsection

@section('content')

{{-- Summary Cards --}}
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 24px;">
  <div class="stat-card green">
    <div class="stat-icon green"></div>
    <div class="stat-label">Total Bulan Ini</div>
    <div class="stat-value green">Rp {{ number_format($monthlyTotal, 0, ',', '.') }}</div>
    <div class="stat-sub">{{ now()->format('F Y') }}</div>
  </div>
  <div class="stat-card gold">
    <div class="stat-icon gold"></div>
    <div class="stat-label">Total Filter Aktif</div>
    <div class="stat-value gold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
    <div class="stat-sub">{{ $incomes->total() }} transaksi</div>
  </div>
  <div class="stat-card blue">
    <div class="stat-icon blue"></div>
    <div class="stat-label">Rata-rata per Transaksi</div>
    <div class="stat-value blue">
      Rp {{ $incomes->total() > 0 ? number_format($totalAmount / $incomes->total(), 0, ',', '.') : '0' }}
    </div>
    <div class="stat-sub">Dari {{ $incomes->total() }} transaksi</div>
  </div>
</div>

{{-- Filter Bar --}}
<div class="card" style="margin-bottom: 20px; padding: 20px;">
  <form action="{{ route('incomes.index') }}" method="GET">
    <div class="filter-bar">
      <div class="form-group">
        <label class="form-label"> Bulan</label>
        <input type="month" name="month" value="{{ request('month') }}" class="form-control">
      </div>
      <div class="form-group">
        <label class="form-label">Kategori</label>
        <select name="category" class="form-control">
          <option value="">Semua Kategori</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div style="display:flex; gap:8px; align-items:flex-end;">
        <button type="submit" class="btn btn-gold">Filter</button>
        <a href="{{ route('incomes.index') }}" class="btn btn-outline">Reset</a>
      </div>
    </div>
  </form>
</div>

{{-- Table --}}
<div class="card">
  @if($incomes->count() > 0)
    <div class="table-wrapper">
      <div class="table-responsive">
        <table>
        <thead>
          <tr>
            <th>Keterangan</th>
            <th>Sumber</th>
            <th>Kategori</th>
            <th>Tanggal</th>
            <th>Rutin?</th>
            <th>Jumlah</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($incomes as $income)
          <tr>
            <td>
              <div style="font-weight:600;color:var(--text-100);">{{ $income->title }}</div>
              @if($income->notes)
                <div style="font-size:11px;color:var(--text-300);margin-top:2px;">{{ Str::limit($income->notes, 50) }}</div>
              @endif
            </td>
            <td>{{ $income->source ?? '—' }}</td>
            <td>
              @if($income->category)
                <span class="badge badge-gold">{{ $income->category->name }}</span>
              @else
                <span style="color:var(--text-400);">—</span>
              @endif
            </td>
            <td>{{ $income->date->format('d M Y') }}</td>
            <td>
              @if($income->is_recurring)
                <span class="badge badge-blue"> {{ ucfirst($income->recurrence) }}</span>
              @else
                <span style="color:var(--text-400);">—</span>
              @endif
            </td>
            <td class="amount-positive" style="font-size:15px;">
              +Rp {{ number_format($income->amount, 0, ',', '.') }}
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('incomes.edit', $income) }}" class="btn btn-outline btn-sm">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>
                <form action="{{ route('incomes.destroy', $income) }}" method="POST" onsubmit="return confirm('Hapus pemasukan ini?')">
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
    <div class="pagination">
      {{ $incomes->links('vendor.pagination.custom') }}
    </div>
  @else
    <div class="empty-state">
      <div class="empty-icon"></div>
      <h3>Belum ada pemasukan</h3>
      <p>Mulai catat sumber pendapatan Anda</p>
      <a href="{{ route('incomes.create') }}" class="btn btn-gold">+ Tambah Pemasukan</a>
    </div>
  @endif
</div>
@endsection
