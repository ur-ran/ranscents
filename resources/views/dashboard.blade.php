@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Your financial summary — ' . now()->format("F Y"))

@section('topbar-actions')
  <a href="{{ route('incomes.create') }}" class="btn btn-outline btn-sm">+ Pemasukan</a>
  <a href="{{ route('expenses.create') }}" class="btn btn-gold btn-sm">+ Pengeluaran</a>
@endsection

@section('content')
<div style="animation: fadeInUp 0.5s ease;">

  {{-- STAT CARDS --}}
  <div class="stats-grid">
    <div class="stat-card green">
      <div class="stat-icon green"></div>
      <div class="stat-label">Pemasukan Bulan Ini</div>
      <div class="stat-value green">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</div>
      <div class="stat-sub">Total pendapatan bulan ini</div>
    </div>

    <div class="stat-card red">
      <div class="stat-icon red"></div>
      <div class="stat-label">Pengeluaran Bulan Ini</div>
      <div class="stat-value red">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</div>
      <div class="stat-sub">Total pengeluaran bulan ini</div>
    </div>

    <div class="stat-card {{ $balance >= 0 ? 'gold' : 'red' }}">
      <div class="stat-icon gold">️</div>
      <div class="stat-label">Saldo Bersih</div>
      <div class="stat-value {{ $balance >= 0 ? 'gold' : 'red' }}">
        {{ $balance >= 0 ? '+' : '-' }}Rp {{ number_format(abs($balance), 0, ',', '.') }}
      </div>
      <div class="stat-sub">{{ $balance >= 0 ? ' Surplus' : '️ Defisit' }}</div>
    </div>

    <div class="stat-card {{ $overdueDebts > 0 ? 'red' : 'blue' }}">
      <div class="stat-icon {{ $overdueDebts > 0 ? 'red' : 'blue' }}"></div>
      <div class="stat-label">Utang Aktif</div>
      <div class="stat-value {{ $overdueDebts > 0 ? 'red' : 'blue' }}">
        Rp {{ number_format($totalOwed, 0, ',', '.') }}
      </div>
      <div class="stat-sub">
        @if($overdueDebts > 0)
          ️ {{ $overdueDebts }} jatuh tempo!
        @else
          Semua tepat waktu
        @endif
      </div>
    </div>

    <div class="stat-card blue">
      <div class="stat-icon blue"></div>
      <div class="stat-label">Piutang Aktif</div>
      <div class="stat-value blue">Rp {{ number_format($totalLent, 0, ',', '.') }}</div>
      <div class="stat-sub">Uang yang dipinjamkan</div>
    </div>

    <div class="stat-card purple">
      <div class="stat-icon purple">️</div>
      <div class="stat-label">Wishlist</div>
      <div class="stat-value purple">{{ $wishlistCount }} item</div>
      <div class="stat-sub">
        @if($urgentWishlist > 0)
           {{ $urgentWishlist }} mendesak
        @else
          Rp {{ number_format($wishlistTotal, 0, ',', '.') }} total
        @endif
      </div>
    </div>
  </div>

  {{-- CHARTS ROW --}}
  <div class="grid-2" style="margin-bottom: 24px;">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"> Arus Keuangan 6 Bulan</h3>
      </div>
      <canvas id="cashflowChart" height="200"></canvas>
    </div>

    <div class="card">
      <div class="card-header">
        <h3 class="card-title"> Pengeluaran per Kategori</h3>
        <span style="font-size:12px;color:var(--text-300);">Bulan ini</span>
      </div>
      @if($expenseByCategory->count() > 0)
        <canvas id="expensePieChart" height="200"></canvas>
      @else
        <div class="empty-state" style="padding: 30px;">
          <div class="empty-icon"></div>
          <p>Belum ada pengeluaran bulan ini</p>
        </div>
      @endif
    </div>
  </div>

  {{-- RECENT TRANSACTIONS --}}
  <div class="grid-2">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"> Pemasukan Terbaru</h3>
        <a href="{{ route('incomes.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
      </div>
      @if($recentIncomes->count() > 0)
        <div class="table-wrapper">
          <div class="table-responsive">
            <table>
            <thead>
              <tr>
                <th>Keterangan</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach($recentIncomes as $income)
              <tr>
                <td>
                  <div style="font-weight:600;color:var(--text-100);">{{ $income->title }}</div>
                  @if($income->source)
                    <div style="font-size:12px;color:var(--text-300);">{{ $income->source }}</div>
                  @endif
                </td>
                <td style="font-size:13px;">{{ $income->date->format('d M') }}</td>
                <td class="amount-positive">+Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
              </tr>
              @endforeach
            </tbody>
            </table>
          </div>
        </div>
      @else
        <div class="empty-state" style="padding: 30px;">
          <div class="empty-icon"></div>
          <p>Belum ada pemasukan</p>
          <a href="{{ route('incomes.create') }}" class="btn btn-gold btn-sm">+ Tambah</a>
        </div>
      @endif
    </div>

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">️ Pengeluaran Terbaru</h3>
        <a href="{{ route('expenses.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
      </div>
      @if($recentExpenses->count() > 0)
        <div class="table-wrapper">
          <div class="table-responsive">
            <table>
            <thead>
              <tr>
                <th>Keterangan</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach($recentExpenses as $expense)
              <tr>
                <td>
                  <div style="font-weight:600;color:var(--text-100);">{{ $expense->title }}</div>
                  @if($expense->category)
                    <div style="font-size:12px;color:var(--text-300);">{{ $expense->category->name }}</div>
                  @endif
                </td>
                <td style="font-size:13px;">{{ $expense->date->format('d M') }}</td>
                <td class="amount-negative">-Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
              </tr>
              @endforeach
            </tbody>
            </table>
          </div>
        </div>
      @else
        <div class="empty-state" style="padding: 30px;">
          <div class="empty-icon"></div>
          <p>Belum ada pengeluaran</p>
          <a href="{{ route('expenses.create') }}" class="btn btn-gold btn-sm">+ Tambah</a>
        </div>
      @endif
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
const chartData = @json($chartData);
const expenseData = @json($expenseByCategory);

// Cashflow Chart
const ctx1 = document.getElementById('cashflowChart');
if (ctx1) {
  new Chart(ctx1, {
    type: 'bar',
    data: {
      labels: chartData.map(d => d.month),
      datasets: [
        {
          label: 'Pemasukan',
          data: chartData.map(d => d.income),
          backgroundColor: 'rgba(34, 197, 94, 0.7)',
          borderColor: '#22C55E',
          borderWidth: 2,
          borderRadius: 6,
        },
        {
          label: 'Pengeluaran',
          data: chartData.map(d => d.expense),
          backgroundColor: 'rgba(239, 68, 68, 0.7)',
          borderColor: '#EF4444',
          borderWidth: 2,
          borderRadius: 6,
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          labels: { color: '#D4C8B0', font: { family: 'Inter' } }
        },
        tooltip: {
          callbacks: {
            label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
          }
        }
      },
      scales: {
        x: {
          ticks: { color: '#9A8F78' },
          grid: { color: 'rgba(212,175,55,0.08)' }
        },
        y: {
          ticks: {
            color: '#9A8F78',
            callback: val => 'Rp ' + (val/1000000 >= 1 ? (val/1000000).toFixed(1)+'jt' : val.toLocaleString('id-ID'))
          },
          grid: { color: 'rgba(212,175,55,0.08)' }
        }
      }
    }
  });
}

// Expense Pie Chart
const ctx2 = document.getElementById('expensePieChart');
if (ctx2 && Object.keys(expenseData).length > 0) {
  const colors = ['#D4AF37','#EF4444','#3B82F6','#22C55E','#8B5CF6','#F59E0B'];
  new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: Object.keys(expenseData),
      datasets: [{
        data: Object.values(expenseData),
        backgroundColor: colors,
        borderWidth: 2,
        borderColor: '#1A1A26',
      }]
    },
    options: {
      responsive: true,
      cutout: '65%',
      plugins: {
        legend: {
          position: 'right',
          labels: {
            color: '#D4C8B0',
            font: { family: 'Inter', size: 12 },
            padding: 12,
          }
        },
        tooltip: {
          callbacks: {
            label: ctx => ctx.label + ': Rp ' + ctx.raw.toLocaleString('id-ID')
          }
        }
      }
    }
  });
}
</script>
@endsection
