<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Category;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    private function userQuery()
    {
        return Income::where('user_id', auth()->id() ?? -1);
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

        $totalAmount  = (clone $query)->sum('amount');
        $incomes      = $query->paginate(15)->withQueryString();
        $categories   = Category::where('type', 'income')->get();
        $monthlyTotal = $this->userQuery()->whereYear('date', now()->year)->whereMonth('date', now()->month)->sum('amount');

        return view('incomes.index', compact('incomes', 'categories', 'totalAmount', 'monthlyTotal'));
    }

    public function create()
    {
        $categories = Category::where('type', 'income')->get();
        return view('incomes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
            'source'      => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'notes'       => 'nullable|string',
            'is_recurring'=> 'boolean',
            'recurrence'  => 'nullable|in:daily,weekly,monthly,yearly',
        ]);

        Income::create(array_merge($data, ['user_id' => auth()->id()]));
        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil ditambahkan!');
    }

    public function edit(Income $income)
    {
        abort_if($income->user_id !== auth()->id(), 403);
        $categories = Category::where('type', 'income')->get();
        return view('incomes.edit', compact('income', 'categories'));
    }

    public function update(Request $request, Income $income)
    {
        abort_if($income->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
            'source'      => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'notes'       => 'nullable|string',
            'is_recurring'=> 'boolean',
            'recurrence'  => 'nullable|in:daily,weekly,monthly,yearly',
        ]);

        $income->update($data);
        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil diperbarui!');
    }

    public function destroy(Income $income)
    {
        abort_if($income->user_id !== auth()->id(), 403);
        $income->delete();
        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil dihapus.');
    }
}
