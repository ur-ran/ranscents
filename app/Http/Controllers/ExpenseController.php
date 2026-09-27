<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    private function userQuery()
    {
        return Expense::where('user_id', auth()->id() ?? -1);
    }

    public function index(Request $request)
    {
        $query = $this->userQuery()->with('category')->orderBy('date', 'desc');

        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('date', $year)->whereMonth('date', $month);
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $totalAmount  = (clone $query)->sum('amount');
        $expenses     = $query->paginate(15)->withQueryString();
        $categories   = Category::where('type', 'expense')->get();
        $monthlyTotal = $this->userQuery()->whereYear('date', now()->year)->whereMonth('date', now()->month)->sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'totalAmount', 'monthlyTotal'));
    }

    public function create()
    {
        $categories = Category::where('type', 'expense')->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
            'date'           => 'required|date',
            'category_id'    => 'nullable|exists:categories,id',
            'notes'          => 'nullable|string',
            'payment_method' => 'required|in:cash,transfer,card,ewallet',
            'is_recurring'   => 'boolean',
            'recurrence'     => 'nullable|in:daily,weekly,monthly,yearly',
        ]);

        Expense::create(array_merge($data, ['user_id' => auth()->id()]));
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function edit(Expense $expense)
    {
        abort_if($expense->user_id !== auth()->id(), 403);
        $categories = Category::where('type', 'expense')->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        abort_if($expense->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
            'date'           => 'required|date',
            'category_id'    => 'nullable|exists:categories,id',
            'notes'          => 'nullable|string',
            'payment_method' => 'required|in:cash,transfer,card,ewallet',
            'is_recurring'   => 'boolean',
            'recurrence'     => 'nullable|in:daily,weekly,monthly,yearly',
        ]);

        $expense->update($data);
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    public function destroy(Expense $expense)
    {
        abort_if($expense->user_id !== auth()->id(), 403);
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
