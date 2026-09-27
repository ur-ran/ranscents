<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    private function userQuery()
    {
        return Wishlist::where('user_id', auth()->id() ?? -1);
    }

    public function index(Request $request)
    {
        $query = $this->userQuery();

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('purchased')) {
            $query->where('is_purchased', $request->purchased == '1');
        } else {
            $query->where('is_purchased', false);
        }

        $priorityOrder = ['urgent' => 1, 'important' => 2, 'need' => 3, 'want' => 4, 'low' => 5];
        $wishlists = $query->get()->sortBy(fn($w) => $priorityOrder[$w->priority] ?? 99);

        $totalEstimated = $this->userQuery()->where('is_purchased', false)->sum('estimated_price');
        $totalSaved     = $this->userQuery()->where('is_purchased', false)->sum('saved_amount');
        $purchasedCount = $this->userQuery()->where('is_purchased', true)->count();

        $grouped = $wishlists->groupBy('priority');

        return view('wishlists.index', compact('wishlists', 'grouped', 'totalEstimated', 'totalSaved', 'purchasedCount'));
    }

    public function create()
    {
        return view('wishlists.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'estimated_price' => 'nullable|numeric|min:0',
            'priority'        => 'required|in:urgent,important,need,want,low',
            'category'        => 'nullable|string|max:100',
            'notes'           => 'nullable|string',
            'url'             => 'nullable|url',
            'store'           => 'nullable|string|max:255',
            'target_date'     => 'nullable|date',
            'saved_amount'    => 'nullable|numeric|min:0',
        ]);

        Wishlist::create(array_merge($data, ['user_id' => auth()->id()]));
        return redirect()->route('wishlists.index')->with('success', 'Item wishlist berhasil ditambahkan!');
    }

    public function edit(Wishlist $wishlist)
    {
        abort_if($wishlist->user_id !== auth()->id(), 403);
        return view('wishlists.edit', compact('wishlist'));
    }

    public function update(Request $request, Wishlist $wishlist)
    {
        abort_if($wishlist->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'estimated_price' => 'nullable|numeric|min:0',
            'priority'        => 'required|in:urgent,important,need,want,low',
            'category'        => 'nullable|string|max:100',
            'notes'           => 'nullable|string',
            'url'             => 'nullable|url',
            'store'           => 'nullable|string|max:255',
            'target_date'     => 'nullable|date',
            'saved_amount'    => 'nullable|numeric|min:0',
            'is_purchased'    => 'boolean',
        ]);

        $wishlist->update($data);
        return redirect()->route('wishlists.index')->with('success', 'Item wishlist berhasil diperbarui!');
    }

    public function destroy(Wishlist $wishlist)
    {
        abort_if($wishlist->user_id !== auth()->id(), 403);
        $wishlist->delete();
        return redirect()->route('wishlists.index')->with('success', 'Item berhasil dihapus.');
    }

    public function purchase(Wishlist $wishlist)
    {
        abort_if($wishlist->user_id !== auth()->id(), 403);
        $wishlist->update(['is_purchased' => true]);

        // Create an Expense record automatically
        \App\Models\Expense::create([
            'user_id' => auth()->id(),
            'title' => 'Membeli: ' . $wishlist->name,
            'amount' => $wishlist->estimated_price ?? 0,
            'date' => now(),
            'notes' => 'Otomatis dibuat dari wishlist.',
            'payment_method' => 'cash',
        ]);

        return redirect()->route('wishlists.index')->with('success', "Selamat! {$wishlist->name} sudah dibeli! Saldo telah dipotong otomatis.");
    }
}
