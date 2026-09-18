<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PsakCreditPortfolio;
use App\Models\PsakMacroParameter;

class PsakStressTestController extends Controller
{
    public function index()
    {
        $baseEad = PsakCreditPortfolio::sum('outstanding_balance');
        $baseEcl = PsakCreditPortfolio::sum('ecl_allowance');
        
        $stage1Ead = PsakCreditPortfolio::where('stage', 1)->sum('outstanding_balance');
        $stage2Ead = PsakCreditPortfolio::where('stage', 2)->sum('outstanding_balance');
        $stage3Ead = PsakCreditPortfolio::where('stage', 3)->sum('outstanding_balance');

        $scenarios = PsakMacroParameter::all();

        // Standard capital values
        $tier1Capital = 1850000000.00; // Modal Inti Bank
        $totalRwa = 9500000000.00; // Aktiva Tertimbang Menurut Risiko (ATMR)
        $baselineCar = ($tier1Capital / $totalRwa) * 100; // 19.47%

        return view('psak.stresstest', compact(
            'baseEad',
            'baseEcl',
            'stage1Ead',
            'stage2Ead',
            'stage3Ead',
            'scenarios',
            'tier1Capital',
            'totalRwa',
            'baselineCar'
        ));
    }

    public function simulate(Request $request)
    {
        $biRate = floatval($request->input('bi_rate', 6.25));
        $inflation = floatval($request->input('inflation', 2.80));
        $gdp = floatval($request->input('gdp', 5.10));

        $stage1Ead = PsakCreditPortfolio::where('stage', 1)->sum('outstanding_balance');
        $stage2Ead = PsakCreditPortfolio::where('stage', 2)->sum('outstanding_balance');
        $stage3Ead = PsakCreditPortfolio::where('stage', 3)->sum('outstanding_balance');
        $baseEcl = PsakCreditPortfolio::sum('ecl_allowance');

        // Macro Stress Factor Calculation
        // Rising BI rate (>6.25%) + Inflation (>3.0%) + GDP drop (<5%) increases default probabilities
        $stressDelta = max(0, ($biRate - 6.25) * 1.5) + max(0, ($inflation - 2.80) * 1.2) + max(0, (5.10 - $gdp) * 2.0);
        $migrationToStage2Rate = min(0.40, ($stressDelta * 0.04));
        $migrationToStage3Rate = min(0.30, ($stressDelta * 0.03));

        // Shift EAD
        $migratedFrom1to2 = $stage1Ead * $migrationToStage2Rate;
        $migratedFrom2to3 = $stage2Ead * $migrationToStage3Rate;

        $newStage1Ead = $stage1Ead - $migratedFrom1to2;
        $newStage2Ead = ($stage2Ead - $migratedFrom2to3) + $migratedFrom1to2;
        $newStage3Ead = $stage3Ead + $migratedFrom2to3;

        // Recompute Stress ECL
        $newEclStage1 = $newStage1Ead * 0.0185 * 0.45;
        $newEclStage2 = $newStage2Ead * 0.2200 * 0.55;
        $newEclStage3 = $newStage3Ead * 0.8500 * 0.70;
        $newTotalEcl = $newEclStage1 + $newEclStage2 + $newEclStage3;

        $eclIncrease = max(0, $newTotalEcl - $baseEcl);

        // Capital impact
        $tier1Capital = 1850000000.00 - $eclIncrease;
        $totalRwa = 9500000000.00;
        $newCar = ($tier1Capital / $totalRwa) * 100;

        return response()->json([
            'stressDelta' => round($stressDelta, 2),
            'migratedFrom1to2' => round($migratedFrom1to2, 2),
            'migratedFrom2to3' => round($migratedFrom2to3, 2),
            'newStage1Ead' => round($newStage1Ead, 2),
            'newStage2Ead' => round($newStage2Ead, 2),
            'newStage3Ead' => round($newStage3Ead, 2),
            'baseEcl' => round($baseEcl, 2),
            'newTotalEcl' => round($newTotalEcl, 2),
            'eclIncrease' => round($eclIncrease, 2),
            'newCar' => round($newCar, 2),
            'status' => $newCar >= 12.0 ? 'Aman (Regulasi OJK > 12%)' : ($newCar >= 8.0 ? 'Dalam Pengawasan Khusus' : 'Peringatan Defisit Modal'),
        ]);
    }
}
