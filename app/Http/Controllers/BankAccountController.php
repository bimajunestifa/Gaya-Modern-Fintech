<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\BankCard;
use App\Models\AuditLog;

class BankAccountController extends Controller
{
    public function index()
    {
        $accounts = BankAccount::withCount('transactions')->orderBy('balance', 'desc')->get();
        $totalBalance = BankAccount::sum('balance');
        $activeAccounts = BankAccount::where('status', 'Active')->count();
        $cards = BankCard::with('account')->get();

        return view('accounts.index', compact('accounts', 'totalBalance', 'activeAccounts', 'cards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_number' => 'required|unique:bank_accounts,account_number',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|string',
            'balance' => 'required|numeric|min:0',
        ]);

        $validated['currency'] = 'IDR';
        $validated['status'] = 'Active';

        $account = BankAccount::create($validated);

        AuditLog::create([
            'action' => 'CREATE_ACCOUNT',
            'user_name' => 'Bank Officer',
            'ip_address' => $request->ip(),
            'details' => "Membuka rekening baru: {$account->account_number} a.n {$account->account_name} dengan saldo awal Rp " . number_format($account->balance, 0, ',', '.'),
        ]);

        return back()->with('success', "Rekening {$account->account_number} berhasil didaftarkan!");
    }
}
