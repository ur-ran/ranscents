<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Debt;
use App\Models\Wishlist;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? -1;
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth   = $now->copy()->endOfMonth();

        // Monthly totals (scoped to user)
        $monthlyIncome  = Income::where('user_id', $userId)->whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount');
        $monthlyExpense = Expense::where('user_id', $userId)->whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount');
        $balance        = $monthlyIncome - $monthlyExpense;

        // Debt summary
        $totalOwed    = Debt::where('user_id', $userId)->where('type', 'owe')->where('status', '!=', 'paid')
                            ->selectRaw('SUM(amount - paid_amount) as r')->value('r') ?? 0;
        $totalLent    = Debt::where('user_id', $userId)->where('type', 'lend')->where('status', '!=', 'paid')
                            ->selectRaw('SUM(amount - paid_amount) as r')->value('r') ?? 0;
        $overdueDebts = Debt::where('user_id', $userId)->where('status', '!=', 'paid')
                            ->where('due_date', '<', $now->toDateString())->count();

        // Wishlist summary
        $wishlistCount   = Wishlist::where('user_id', $userId)->where('is_purchased', false)->count();
        $urgentWishlist  = Wishlist::where('user_id', $userId)->where('is_purchased', false)->where('priority', 'urgent')->count();
        $wishlistTotal   = Wishlist::where('user_id', $userId)->where('is_purchased', false)->sum('estimated_price');

        // Recent transactions
        $recentIncomes  = Income::with('category')->where('user_id', $userId)->orderBy('date', 'desc')->limit(5)->get();
        $recentExpenses = Expense::with('category')->where('user_id', $userId)->orderBy('date', 'desc')->limit(5)->get();

        // Monthly chart data (last 6 months)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $chartData[] = [
                'month'   => $month->format('M Y'),
                'income'  => Income::where('user_id', $userId)->whereYear('date', $month->year)->whereMonth('date', $month->month)->sum('amount'),
                'expense' => Expense::where('user_id', $userId)->whereYear('date', $month->year)->whereMonth('date', $month->month)->sum('amount'),
            ];
        }

        // Expense by category this month
        $expenseByCategory = Expense::with('category')
            ->where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(fn($e) => $e->category?->name ?? 'Lainnya')
            ->map(fn($items) => $items->sum('amount'))
            ->sortDesc()
            ->take(6);

        return view('dashboard', compact(
            'monthlyIncome', 'monthlyExpense', 'balance',
            'totalOwed', 'totalLent', 'overdueDebts',
            'wishlistCount', 'urgentWishlist', 'wishlistTotal',
            'recentIncomes', 'recentExpenses', 'chartData', 'expenseByCategory'
        ));
    }
}
