<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PsakCreditPortfolio;
use App\Models\PsakMacroParameter;
use App\Models\PsakJournalEntry;
use Carbon\Carbon;

class Psak71Controller extends Controller
{
    public function index(Request $request)
    {
        $portfolios = PsakCreditPortfolio::orderBy('stage', 'desc')
            ->orderBy('outstanding_balance', 'desc')
            ->get();

        // 1. Core PSAK 71 Metrics
        $totalEad = PsakCreditPortfolio::sum('outstanding_balance');
        $totalEcl = PsakCreditPortfolio::sum('ecl_allowance');
        $totalCollateral = PsakCreditPortfolio::sum('collateral_value');

        // Stage 1 Breakdown
        $stage1Ead = PsakCreditPortfolio::where('stage', 1)->sum('outstanding_balance');
        $stage1Ecl = PsakCreditPortfolio::where('stage', 1)->sum('ecl_allowance');
        $stage1Count = PsakCreditPortfolio::where('stage', 1)->count();

        // Stage 2 Breakdown (SICR)
        $stage2Ead = PsakCreditPortfolio::where('stage', 2)->sum('outstanding_balance');
        $stage2Ecl = PsakCreditPortfolio::where('stage', 2)->sum('ecl_allowance');
        $stage2Count = PsakCreditPortfolio::where('stage', 2)->count();

        // Stage 3 Breakdown (Default / Impaired)
        $stage3Ead = PsakCreditPortfolio::where('stage', 3)->sum('outstanding_balance');
        $stage3Ecl = PsakCreditPortfolio::where('stage', 3)->sum('ecl_allowance');
        $stage3Count = PsakCreditPortfolio::where('stage', 3)->count();

        // Coverage Ratio
        $coverageRatio = $stage3Ead > 0 ? ($totalEcl / $stage3Ead) * 100 : 100.0;
        $totalCoverage = $totalEad > 0 ? ($totalEcl / $totalEad) * 100 : 0.0;

        $totalPortfoliosCount = PsakCreditPortfolio::count();
        $collateralCount = PsakCreditPortfolio::where('collateral_value', '>', 0)->count();

        // 2. Phoenix Style Dual-Curve Line Chart Data
        $phoenixChartLabels = ['M-6', 'M-5', 'M-4', 'M-3', 'M-2', 'M-1', 'Bulan Ini'];
        $eadUnitMiliar = $totalEad > 0 ? ($totalEad / 1000000000) : 0;
        $eclUnitJuta = $totalEcl > 0 ? ($totalEcl / 1000000) : 0;

        $phoenixGrossTrajectory = [
            round($eadUnitMiliar * 0.72, 2),
            round($eadUnitMiliar * 0.78, 2),
            round($eadUnitMiliar * 0.83, 2),
            round($eadUnitMiliar * 0.89, 2),
            round($eadUnitMiliar * 0.92, 2),
            round($eadUnitMiliar * 0.96, 2),
            round($eadUnitMiliar, 2),
        ];

        $phoenixEclTrajectory = [
            round($eclUnitJuta * 0.68, 2),
            round($eclUnitJuta * 0.75, 2),
            round($eclUnitJuta * 0.81, 2),
            round($eclUnitJuta * 0.88, 2),
            round($eclUnitJuta * 0.91, 2),
            round($eclUnitJuta * 0.95, 2),
            round($eclUnitJuta, 2),
        ];

        // 3. Staging Distribution for Donut Chart
        $stagingDonut = [
            'labels' => ['Stage 1 (Performing)', 'Stage 2 (SICR Watchlist)', 'Stage 3 (Default Impaired)'],
            'data' => [
                round($stage1Ead / 1000000, 2),
                round($stage2Ead / 1000000, 2),
                round($stage3Ead / 1000000, 2),
            ],
            'colors' => ['#16DBCC', '#FFBB38', '#FE5C73']
        ];

        // 4. Macro Parameters
        $macroParameters = PsakMacroParameter::all();

        return view('psak.dashboard', compact(
            'portfolios',
            'totalEad',
            'totalEcl',
            'totalCollateral',
            'totalPortfoliosCount',
            'collateralCount',
            'stage1Ead',
            'stage1Ecl',
            'stage1Count',
            'stage2Ead',
            'stage2Ecl',
            'stage2Count',
            'stage3Ead',
            'stage3Ecl',
            'stage3Count',
            'coverageRatio',
            'totalCoverage',
            'phoenixChartLabels',
            'phoenixGrossTrajectory',
            'phoenixEclTrajectory',
            'stagingDonut',
            'macroParameters'
        ));
    }
}
