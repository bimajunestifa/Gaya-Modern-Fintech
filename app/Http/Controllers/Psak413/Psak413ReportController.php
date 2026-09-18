<?php

namespace App\Http\Controllers\Psak413;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Psak413CreditPortfolio;
use Illuminate\Support\Facades\DB;

class Psak413ReportController extends Controller
{
    public function index()
    {
        // 1. Total Aggregates
        $totalGrossFinancing = Psak413CreditPortfolio::sum('outstanding_principal');
        $totalMarginSuspended = Psak413CreditPortfolio::sum('margin_suspended');
        $totalNetCarrying = Psak413CreditPortfolio::sum('net_carrying_amount');
        $totalEcl = Psak413CreditPortfolio::sum('ecl_allowance');
        $totalKafalahProvision = Psak413CreditPortfolio::sum('kafalah_provision_amount');
        $totalKafalahGuarantee = Psak413CreditPortfolio::sum('kafalah_guarantee_amount');
        $netFinancingAsset = $totalNetCarrying - $totalEcl;

        // 2. Breakdown per Contract Type (Pengungkapan Akad PSAK 413)
        $contractBreakdowns = Psak413CreditPortfolio::select(
            'contract_type',
            DB::raw('COUNT(*) as total_accounts'),
            DB::raw('SUM(outstanding_principal) as gross_amount'),
            DB::raw('SUM(margin_suspended) as margin_suspended_amount'),
            DB::raw('SUM(net_carrying_amount) as net_amount'),
            DB::raw('SUM(collateral_value) as collateral_amount'),
            DB::raw('SUM(CASE WHEN stage = 1 THEN net_carrying_amount ELSE 0 END) as stage1_amount'),
            DB::raw('SUM(CASE WHEN stage = 1 THEN ecl_allowance ELSE 0 END) as stage1_ecl'),
            DB::raw('SUM(CASE WHEN stage = 2 THEN net_carrying_amount ELSE 0 END) as stage2_amount'),
            DB::raw('SUM(CASE WHEN stage = 2 THEN ecl_allowance ELSE 0 END) as stage2_ecl'),
            DB::raw('SUM(CASE WHEN stage = 3 THEN net_carrying_amount ELSE 0 END) as stage3_amount'),
            DB::raw('SUM(CASE WHEN stage = 3 THEN ecl_allowance ELSE 0 END) as stage3_ecl'),
            DB::raw('SUM(ecl_allowance) as total_ecl'),
            DB::raw('SUM(kafalah_provision_amount) as total_kafalah')
        )->groupBy('contract_type')->get();

        // 3. Staging Summary
        $stageSummary = [
            'stage1' => [
                'exposure' => Psak413CreditPortfolio::where('stage', 1)->sum('net_carrying_amount'),
                'ecl' => Psak413CreditPortfolio::where('stage', 1)->sum('ecl_allowance'),
                'count' => Psak413CreditPortfolio::where('stage', 1)->count(),
            ],
            'stage2' => [
                'exposure' => Psak413CreditPortfolio::where('stage', 2)->sum('net_carrying_amount'),
                'ecl' => Psak413CreditPortfolio::where('stage', 2)->sum('ecl_allowance'),
                'count' => Psak413CreditPortfolio::where('stage', 2)->count(),
            ],
            'stage3' => [
                'exposure' => Psak413CreditPortfolio::where('stage', 3)->sum('net_carrying_amount'),
                'ecl' => Psak413CreditPortfolio::where('stage', 3)->sum('ecl_allowance'),
                'count' => Psak413CreditPortfolio::where('stage', 3)->count(),
            ],
        ];

        return view('psak413.reports', compact(
            'totalGrossFinancing',
            'totalMarginSuspended',
            'totalNetCarrying',
            'totalEcl',
            'totalKafalahProvision',
            'totalKafalahGuarantee',
            'netFinancingAsset',
            'contractBreakdowns',
            'stageSummary'
        ));
    }
}

