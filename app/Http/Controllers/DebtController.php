<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    private function userQuery()
    {
        return Debt::where('user_id', auth()->id() ?? -1);
    }

    public function index(Request $request)
    {
        $query = $this->userQuery()->orderBy('status')->orderBy('due_date');

        if ($request->filled('type'))   $query->where('type', $request->type);
        if ($request->filled('status')) $query->where('status', $request->status);

        $debts = $query->paginate(15)->withQueryString();

        $totalOwed    = $this->userQuery()->where('type', 'owe')->where('status', '!=', 'paid')
                            ->selectRaw('SUM(amount - paid_amount) as r')->value('r') ?? 0;
        $totalLent    = $this->userQuery()->where('type', 'lend')->where('status', '!=', 'paid')
                            ->selectRaw('SUM(amount - paid_amount) as r')->value('r') ?? 0;
        $overdueCount = $this->userQuery()->where('status', '!=', 'paid')
                            ->where('due_date', '<', now()->toDateString())->count();

        return view('debts.index', compact('debts', 'totalOwed', 'totalLent', 'overdueCount'));
    }

    public function create()
    {
        return view('debts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'person_name' => 'required|string|max:255',
            'type'        => 'required|in:owe,lend',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
            'due_date'    => 'nullable|date|after_or_equal:date',
            'description' => 'nullable|string',
            'contact'     => 'nullable|string|max:255',
        ]);

        Debt::create(array_merge($data, [
            'user_id'     => auth()->id(),
            'status'      => 'active',
            'paid_amount' => 0,
        ]));

        return redirect()->route('debts.index')->with('success', 'Data utang/piutang berhasil ditambahkan!');
    }

    public function edit(Debt $debt)
    {
        abort_if($debt->user_id !== auth()->id(), 403);
        return view('debts.edit', compact('debt'));
    }

    public function update(Request $request, Debt $debt)
    {
        abort_if($debt->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'person_name' => 'required|string|max:255',
            'type'        => 'required|in:owe,lend',
            'amount'      => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'date'        => 'required|date',
            'due_date'    => 'nullable|date',
            'description' => 'nullable|string',
            'contact'     => 'nullable|string|max:255',
            'status'      => 'required|in:active,partially_paid,paid',
        ]);

        $debt->update($data);
        return redirect()->route('debts.index')->with('success', 'Data utang/piutang berhasil diperbarui!');
    }

    public function destroy(Debt $debt)
    {
        abort_if($debt->user_id !== auth()->id(), 403);
        $debt->delete();
        return redirect()->route('debts.index')->with('success', 'Data berhasil dihapus.');
    }

    public function pay(Request $request, Debt $debt)
    {
        abort_if($debt->user_id !== auth()->id(), 403);

        $request->validate(['pay_amount' => 'required|numeric|min:0']);

        $newPaid = $debt->paid_amount + $request->pay_amount;
        $status  = $newPaid >= $debt->amount ? 'paid' : 'partially_paid';

        $debt->update([
            'paid_amount' => min($newPaid, $debt->amount),
            'status'      => $status,
        ]);

        if ($debt->type == 'owe') {
            \App\Models\Expense::create([
                'title'          => 'Bayar utang: ' . $debt->person_name,
                'amount'         => $request->pay_amount,
                'date'           => now()->toDateString(),
                'payment_method' => 'cash',
                'is_recurring'   => false,
                'user_id'        => auth()->id(),
            ]);
        } elseif ($debt->type == 'lend') {
            \App\Models\Income::create([
                'title'          => 'Terima piutang: ' . $debt->person_name,
                'amount'         => $request->pay_amount,
                'date'           => now()->toDateString(),
                'source'         => 'Piutang',
                'is_recurring'   => false,
                'user_id'        => auth()->id(),
            ]);
        }

        return redirect()->route('debts.index')->with('success', 'Pembayaran berhasil dicatat!');
    }
}
