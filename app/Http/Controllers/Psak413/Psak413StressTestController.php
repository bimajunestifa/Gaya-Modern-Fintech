<?php

namespace App\Http\Controllers\Psak413;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Psak413CreditPortfolio;
use App\Models\Psak413MacroParameter;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class Psak413StressTestController extends Controller
{
    public function index()
    {
        $parameters = Psak413MacroParameter::all()->keyBy('scenario_name');
        
        $totalNet = Psak413CreditPortfolio::sum('net_carrying_amount');
        $baselineEcl = Psak413CreditPortfolio::sum('ecl_allowance');
        $baselineKafalah = Psak413CreditPortfolio::sum('kafalah_provision_amount');
        $stage3Baseline = Psak413CreditPortfolio::where('stage', 3)->sum('net_carrying_amount');
        $baselineNpf = ($totalNet > 0) ? ($stage3Baseline / $totalNet) * 100 : 0;

        return view('psak413.stresstest', compact(
            'parameters',
            'totalNet',
            'baselineEcl',
            'baselineKafalah',
            'baselineNpf'
        ));
    }

    public function simulate(Request $request)
    {
        $scenario = $request->input('scenario', 'moderate');
        $gdp = (float) $request->input('gdp_growth', 3.8);
        $inflation = (float) $request->input('inflation_rate', 4.9);
        $issiChange = (float) $request->input('issi_index_change', -4.2);
        $sbisYield = (float) $request->input('sbis_yield_rate', 7.5);
        $usdIdr = (float) $request->input('usd_idr_rate', 16400);

        // Sharia Stress Shock Factors
        // Gdp slowdown + inflation increase + ISSI drop increases migration & ECL multiplier
        $macroStressIndex = max(1.0, 1.0 + (max(0, 5.0 - $gdp) * 0.18) + (max(0, $inflation - 3.0) * 0.12) + (abs(min(0, $issiChange)) * 0.035));

        $totalNet = Psak413CreditPortfolio::sum('net_carrying_amount');
        $baselineEcl = Psak413CreditPortfolio::sum('ecl_allowance');
        $baselineKafalah = Psak413CreditPortfolio::sum('kafalah_provision_amount');
        $stage1Amount = Psak413CreditPortfolio::where('stage', 1)->sum('net_carrying_amount');
        $stage2Amount = Psak413CreditPortfolio::where('stage', 2)->sum('net_carrying_amount');
        $stage3Amount = Psak413CreditPortfolio::where('stage', 3)->sum('net_carrying_amount');

        // Migration rates
        $s1ToS2Migration = $stage1Amount * (0.05 * $macroStressIndex);
        $s2ToS3Migration = $stage2Amount * (0.08 * $macroStressIndex);

        $simulatedStage1 = max(0, $stage1Amount - $s1ToS2Migration);
        $simulatedStage2 = max(0, $stage2Amount + $s1ToS2Migration - $s2ToS3Migration);
        $simulatedStage3 = max(0, $stage3Amount + $s2ToS3Migration);

        $simulatedEcl = ($baselineEcl * $macroStressIndex * 1.15);
        $simulatedKafalah = ($baselineKafalah * $macroStressIndex * 1.25);
        $totalSimulatedImpairment = $simulatedEcl + $simulatedKafalah;

        $simulatedNpf = ($totalNet > 0) ? ($simulatedStage3 / $totalNet) * 100 : 0;
        $baselineNpf = ($totalNet > 0) ? ($stage3Amount / $totalNet) * 100 : 0;

        $eclDelta = $simulatedEcl - $baselineEcl;
        $capitalImpactPercentage = ($totalNet > 0) ? ($eclDelta / ($totalNet * 0.15)) * 100 : 0; // Assuming 15% Tier-1 Sharia Capital base

        // Log audit
        AuditLog::create([
            'action' => 'PSAK413_STRESS_TEST_SIMULATION',
            'user_name' => Auth::check() ? Auth::user()->name : 'Bank Officer',
            'ip_address' => $request->ip(),
            'details' => "Simulasi Stress Test PSAK 413 Syariah Skenario: {$scenario}. Estimasi CKPN: Rp " . number_format($simulatedEcl, 0, ',', '.') . " (Delta +Rp " . number_format($eclDelta, 0, ',', '.') . ")",
        ]);

        return response()->json([
            'success' => true,
            'scenario' => $scenario,
            'macro_stress_index' => round($macroStressIndex, 2),
            'baseline_ecl' => $baselineEcl,
            'simulated_ecl' => round($simulatedEcl, 2),
            'ecl_delta' => round($eclDelta, 2),
            'baseline_kafalah' => $baselineKafalah,
            'simulated_kafalah' => round($simulatedKafalah, 2),
            'total_simulated_impairment' => round($totalSimulatedImpairment, 2),
            'baseline_npf' => round($baselineNpf, 2),
            'simulated_npf' => round($simulatedNpf, 2),
            'simulated_stage1' => round($simulatedStage1, 2),
            'simulated_stage2' => round($simulatedStage2, 2),
            'simulated_stage3' => round($simulatedStage3, 2),
            'capital_impact_percent' => round($capitalImpactPercentage, 2),
        ]);
    }
}

