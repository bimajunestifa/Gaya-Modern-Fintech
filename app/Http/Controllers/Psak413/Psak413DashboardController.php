<?php

namespace App\Http\Controllers\Psak413;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Psak413CreditPortfolio;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class Psak413DashboardController extends Controller
{
    public function index(Request $request)
    {
        $contractFilter = $request->input('contract_type', 'ALL');
        $stageFilter = $request->input('stage', 'ALL');
        $search = $request->input('search');

        // Query Builder
        $query = Psak413CreditPortfolio::query();

        if ($contractFilter !== 'ALL' && !empty($contractFilter)) {
            $query->where('contract_type', $contractFilter);
        }

        if ($stageFilter !== 'ALL' && !empty($stageFilter)) {
            $query->where('stage', (int) $stageFilter);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('account_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('sector', 'LIKE', "%{$search}%")
                  ->orWhere('contract_type', 'LIKE', "%{$search}%");
            });
        }

        $portfolios = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // High-Level Aggregate Metrics (All Portfolios)
        $totalFinancing = Psak413CreditPortfolio::sum('outstanding_principal');
        $totalNetCarrying = Psak413CreditPortfolio::sum('net_carrying_amount');
        $totalEcl = Psak413CreditPortfolio::sum('ecl_allowance');
        $totalKafalahProvision = Psak413CreditPortfolio::sum('kafalah_provision_amount');
        $totalKafalahGuarantee = Psak413CreditPortfolio::sum('kafalah_guarantee_amount');
        $totalAccounts = Psak413CreditPortfolio::count();

        // Staging Metrics
        $stage1Amount = Psak413CreditPortfolio::where('stage', 1)->sum('net_carrying_amount');
        $stage1Count = Psak413CreditPortfolio::where('stage', 1)->count();
        $stage1Ecl = Psak413CreditPortfolio::where('stage', 1)->sum('ecl_allowance');

        $stage2Amount = Psak413CreditPortfolio::where('stage', 2)->sum('net_carrying_amount');
        $stage2Count = Psak413CreditPortfolio::where('stage', 2)->count();
        $stage2Ecl = Psak413CreditPortfolio::where('stage', 2)->sum('ecl_allowance');

        $stage3Amount = Psak413CreditPortfolio::where('stage', 3)->sum('net_carrying_amount');
        $stage3Count = Psak413CreditPortfolio::where('stage', 3)->count();
        $stage3Ecl = Psak413CreditPortfolio::where('stage', 3)->sum('ecl_allowance');

        // NPF (Non-Performing Financing) Ratio (Stage 3 / Total)
        $npfGross = ($totalNetCarrying > 0) ? ($stage3Amount / $totalNetCarrying) * 100 : 0;
        $npfNet = ($totalNetCarrying > 0) ? max(0, ($stage3Amount - $stage3Ecl) / $totalNetCarrying) * 100 : 0;
        $coverageRatio = ($stage3Amount > 0) ? ($totalEcl / $stage3Amount) * 100 : 100;

        // Breakdown by Contract Type (Murabahah, Musyarakah, Mudharabah, Ijarah, Istishna, Qardh, Kafalah)
        $contractSummary = Psak413CreditPortfolio::select(
            'contract_type',
            DB::raw('COUNT(*) as total_accounts'),
            DB::raw('SUM(outstanding_principal) as total_principal'),
            DB::raw('SUM(net_carrying_amount) as total_net'),
            DB::raw('SUM(ecl_allowance) as total_ecl'),
            DB::raw('SUM(kafalah_provision_amount) as total_kafalah_provision'),
            DB::raw('SUM(CASE WHEN stage = 3 THEN net_carrying_amount ELSE 0 END) as npf_amount')
        )->groupBy('contract_type')->get();

        return view('psak413.dashboard', compact(
            'portfolios',
            'contractFilter',
            'stageFilter',
            'search',
            'totalFinancing',
            'totalNetCarrying',
            'totalEcl',
            'totalKafalahProvision',
            'totalKafalahGuarantee',
            'totalAccounts',
            'stage1Amount',
            'stage1Count',
            'stage1Ecl',
            'stage2Amount',
            'stage2Count',
            'stage2Ecl',
            'stage3Amount',
            'stage3Count',
            'stage3Ecl',
            'npfGross',
            'npfNet',
            'coverageRatio',
            'contractSummary'
        ));
    }
}

