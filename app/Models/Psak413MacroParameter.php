<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psak413MacroParameter extends Model
{
    use HasFactory;

    protected $table = 'psak413_macro_parameters';

    protected $fillable = [
        'scenario_name',
        'gdp_growth',
        'inflation_rate',
        'issi_index_change',
        'sbis_yield_rate',
        'usd_idr_rate',
        'sharia_npf_multiplier',
        'stage2_migration_rate',
        'stage3_migration_rate',
    ];

    protected $casts = [
        'gdp_growth' => 'decimal:2',
        'inflation_rate' => 'decimal:2',
        'issi_index_change' => 'decimal:2',
        'sbis_yield_rate' => 'decimal:2',
        'usd_idr_rate' => 'decimal:2',
        'sharia_npf_multiplier' => 'decimal:2',
        'stage2_migration_rate' => 'decimal:2',
        'stage3_migration_rate' => 'decimal:2',
    ];
}

