<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use Illuminate\Http\Request;

class SavingController extends Controller
{
    public function index(Request $request)
    {
        $savings = auth()->user()->savings()->orderBy('date', 'desc')->paginate(10);
        $totalSavings = auth()->user()->savings()->sum('amount');
        return view('savings.index', compact('savings', 'totalSavings'));
    }

    public function create()
    {
        return view('savings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $saving = auth()->user()->savings()->create($validated);

        // Auto-create expense
        $category = \App\Models\Category::firstOrCreate(
            ['name' => 'Tabungan'],
            [
                'type' => 'expense',
                'icon' => '🏦',
                'color' => '#10B981'
            ]
        );

        auth()->user()->expenses()->create([
            'title' => 'Tabungan: ' . $validated['name'],
            'category_id' => $category->id,
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'notes' => 'Alokasi otomatis ke tabungan'
        ]);

        return redirect()->route('savings.index')->with('success', 'Tabungan berhasil ditambahkan dan dicatat sebagai pengeluaran.');
    }

    public function edit(Saving $saving)
    {
        if ($saving->user_id !== auth()->id()) abort(403);
        return view('savings.edit', compact('saving'));
    }

    public function update(Request $request, Saving $saving)
    {
        if ($saving->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $saving->update($validated);

        return redirect()->route('savings.index')->with('success', 'Tabungan berhasil diubah.');
    }

    public function destroy(Saving $saving)
    {
        if ($saving->user_id !== auth()->id()) abort(403);
        $saving->delete();
        return redirect()->route('savings.index')->with('success', 'Tabungan berhasil dihapus.');
    }
}
