<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psak413CreditPortfolio extends Model
{
    use HasFactory;

    protected $table = 'psak413_credit_portfolios';

    protected $fillable = [
        'account_number',
        'customer_name',
        'contract_type',
        'sector',
        'financing_limit',
        'outstanding_principal',
        'margin_suspended',
        'net_carrying_amount',
        'collateral_value',
        'collateral_type',
        'dpd',
        'is_restructured',
        'stage',
        'pd_rate',
        'lgd_rate',
        'profit_rate',
        'ecl_allowance',
        'kafalah_guarantee_amount',
        'kafalah_provision_amount',
        'valuation_date',
    ];

    protected $casts = [
        'is_restructured' => 'boolean',
        'valuation_date' => 'date',
        'financing_limit' => 'decimal:2',
        'outstanding_principal' => 'decimal:2',
        'margin_suspended' => 'decimal:2',
        'net_carrying_amount' => 'decimal:2',
        'collateral_value' => 'decimal:2',
        'pd_rate' => 'decimal:4',
        'lgd_rate' => 'decimal:4',
        'profit_rate' => 'decimal:4',
        'ecl_allowance' => 'decimal:2',
        'kafalah_guarantee_amount' => 'decimal:2',
        'kafalah_provision_amount' => 'decimal:2',
    ];

    /**
     * Hitung Ekspektasi Kerugian Penurunan Nilai (ECL) & Provisi Kafalah sesuai Standar PSAK 413 (DSAS IAI)
     */
    public static function calculateEclSyariah($contractType, $outstanding, $marginSuspended, $dpd, $isRestructured, $collateralValue, $kafalahGuarantee = 0, $profitRate = 10.50)
    {
        $contractType = strtoupper($contractType);

        // 1. Hitung Nilai Tercatat Neto (Net Carrying Amount)
        if (in_array($contractType, ['MURABAHAH', 'ISTISHNA', 'SALAM'])) {
            $netCarrying = max(0, $outstanding - $marginSuspended);
        } else {
            $netCarrying = $outstanding;
        }

        // 2. Tentukan Staging Syariah
        if ($dpd > 90) {
            $stage = 3; // Impaired / NPF (Non-Performing Financing)
            $basePd = 100.00;
        } elseif ($dpd > 30 || $isRestructured) {
            $stage = 2; // SICR (Significant Increase in Credit Risk)
            $basePd = in_array($contractType, ['MUDHARABAH', 'MUSYARAKAH']) ? 22.50 : 17.50;
        } else {
            $stage = 1; // Performing (12-Month ECL)
            $basePd = in_array($contractType, ['MUDHARABAH', 'MUSYARAKAH']) ? 2.40 : 1.75;
        }

        // 3. Hitung LGD (Loss Given Default) dengan Haircut Agunan Syariah 30% (70% diakui)
        $discountedCollateral = $collateralValue * 0.70;
        $unsecuredExposure = max(0, $netCarrying - $discountedCollateral);

        if ($netCarrying > 0) {
            $lgd = min(100.00, max(20.00, ($unsecuredExposure / $netCarrying) * 100));
        } else {
            $lgd = 40.00;
        }

        // 4. Discount Factor (DF) berbasis Implied Yield / Nisbah Efektif (1 Tahun Horizon)
        $discountFactor = 1 / (1 + ($profitRate / 100));

        // 5. Formula ECL Syariah = Net Carrying Amount * PD% * LGD% * Discount Factor
        $eclAllowance = $netCarrying * ($basePd / 100) * ($lgd / 100) * $discountFactor;

        // 6. Provisi Kafalah (Jika akad Kafalah / Penjaminan Pembiayaan)
        $kafalahProvision = 0;
        if ($contractType === 'KAFALAH' || $kafalahGuarantee > 0) {
            $guaranteeBase = $kafalahGuarantee > 0 ? $kafalahGuarantee : $outstanding;
            // Provisi kontinjen berbasis probabilitas klaim wanprestasi
            $claimProbability = ($stage === 3) ? 0.85 : (($stage === 2) ? 0.35 : 0.05);
            $kafalahProvision = $guaranteeBase * $claimProbability * ($lgd / 100);
        }

        return [
            'contract_type' => $contractType,
            'net_carrying_amount' => round($netCarrying, 2),
            'stage' => $stage,
            'pd_rate' => round($basePd, 4),
            'lgd_rate' => round($lgd, 4),
            'profit_rate' => round($profitRate, 4),
            'ecl_allowance' => round($eclAllowance, 2),
            'kafalah_provision_amount' => round($kafalahProvision, 2),
        ];
    }
}

