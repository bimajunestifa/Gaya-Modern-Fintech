<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PsakJournalEntry;

class PsakJournalController extends Controller
{
    public function index()
    {
        $entries = PsakJournalEntry::orderBy('entry_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        $totalDebit = PsakJournalEntry::sum('debit');
        $totalCredit = PsakJournalEntry::sum('credit');
        $isBalanced = round($totalDebit, 2) == round($totalCredit, 2);

        return view('psak.journals', compact('entries', 'totalDebit', 'totalCredit', 'isBalanced'));
    }
}
