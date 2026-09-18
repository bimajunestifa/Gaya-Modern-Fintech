<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsakCreditPortfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'borrower_name',
        'facility_type',
        'sector',
        'principal_limit',
        'outstanding_balance',
        'collateral_value',
        'collateral_type',
        'dpd',
        'is_restructured',
        'stage',
        'pd_rate',
        'lgd_rate',
        'eir',
        'ecl_allowance',
        'valuation_date',
    ];

    protected $casts = [
        'is_restructured' => 'boolean',
        'valuation_date' => 'date',
        'principal_limit' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'collateral_value' => 'decimal:2',
        'pd_rate' => 'decimal:2',
        'lgd_rate' => 'decimal:2',
        'eir' => 'decimal:2',
        'ecl_allowance' => 'decimal:2',
    ];

    /**
     * Hitung otomatis Staging & Nilai CKPN sesuai standar PSAK 71
     */
    public static function calculateEcl($ead, $dpd, $isRestructured, $collateralValue)
    {
        // 1. Tentukan Staging
        if ($dpd > 90) {
            $stage = 3; // Default / Macet (Impaired)
            $pd = 100.00;
        } elseif ($dpd > 30 || $isRestructured) {
            $stage = 2; // SICR (Significant Increase in Credit Risk)
            $pd = 18.50; // Lifetime PD
        } else {
            $stage = 1; // Performing (12-Month ECL)
            $pd = 1.85; // 12-Month PD
        }

        // 2. LGD (Loss Given Default) dengan memperhitungkan agunan terdiskon (Haircut 30%)
        $discountedCollateral = $collateralValue * 0.70;
        $unsecuredEad = max(0, $ead - $discountedCollateral);
        
        if ($ead > 0) {
            $lgd = min(100.00, max(25.00, ($unsecuredEad / $ead) * 100));
        } else {
            $lgd = 45.00;
        }

        // 3. Formula ECL = EAD * (PD / 100) * (LGD / 100)
        $ecl = $ead * ($pd / 100) * ($lgd / 100);

        return [
            'stage' => $stage,
            'pd_rate' => round($pd, 2),
            'lgd_rate' => round($lgd, 2),
            'ecl_allowance' => round($ecl, 2),
        ];
    }
}

