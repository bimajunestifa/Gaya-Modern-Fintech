<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investment;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::orderBy('principal_invested', 'desc')->get();
        $totalInvested = Investment::sum('principal_invested');
        $totalCurrentValue = Investment::sum('current_value');
        $totalGain = $totalCurrentValue - $totalInvested;
        $gainPercentage = $totalInvested > 0 ? ($totalGain / $totalInvested) * 100 : 0;

        return view('investments.index', compact(
            'investments',
            'totalInvested',
            'totalCurrentValue',
            'totalGain',
            'gainPercentage'
        ));
    }
}
