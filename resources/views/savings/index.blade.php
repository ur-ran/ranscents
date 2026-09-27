@extends('layouts.app')

@section('title', 'Tabungan')
@section('page-title', 'Tabungan')
@section('page-subtitle', 'Catat dan kelola tabungan masa depan Anda')

@section('topbar-actions')
  <a href="{{ route('savings.create') }}" class="btn btn-gold">+ Tambah Tabungan</a>
@endsection

@section('content')

{{-- Summary --}}
<div class="stats-grid" style="grid-template-columns: 1fr; margin-bottom: 24px;">
  <div class="stat-card gold">
    <div class="stat-icon gold">🏦</div>
    <div class="stat-label">Total Tabungan Terkumpul</div>
    <div class="stat-value gold">Rp {{ number_format($totalSavings, 0, ',', '.') }}</div>
    <div class="stat-sub">akumulasi dari semua alokasi tabungan</div>
  </div>
</div>

<div class="card">
  @if($savings->count() > 0)
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Nama Tabungan</th>
            <th>Keterangan</th>
            <th style="text-align: right;">Jumlah</th>
            <th style="text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($savings as $saving)
            <tr>
              <td>{{ $saving->date->format('d M Y') }}</td>
              <td style="font-weight: 600; color: var(--text-100);">{{ $saving->name }}</td>
              <td style="color: var(--text-300);">{{ $saving->description ?? '-' }}</td>
              <td style="text-align: right; color: var(--success); font-weight: 700;">
                + Rp {{ number_format($saving->amount, 0, ',', '.') }}
              </td>
              <td>
                <div style="display: flex; gap: 8px; justify-content: center;">
                  <a href="{{ route('savings.edit', $saving) }}" class="btn btn-outline btn-sm">Edit</a>
                  <form action="{{ route('savings.destroy', $saving) }}" method="POST" onsubmit="return confirm('Hapus data tabungan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    
    <div class="pagination" style="padding: 16px 20px;">
      {{ $savings->links('vendor.pagination.custom') }}
    </div>
  @else
    <div class="empty-state">
      <div class="empty-icon">🏦</div>
      <h3>Belum ada data tabungan</h3>
      <p>Catat setiap alokasi tabungan Anda di sini.</p>
      <a href="{{ route('savings.create') }}" class="btn btn-gold">+ Tambah Tabungan</a>
    </div>
  @endif
</div>

@endsection
