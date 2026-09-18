<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsakMacroParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'scenario_name',
        'weight_percentage',
        'bi_rate',
        'inflation_rate',
        'gdp_growth',
    ];

    protected $casts = [
        'weight_percentage' => 'decimal:2',
        'bi_rate' => 'decimal:2',
        'inflation_rate' => 'decimal:2',
        'gdp_growth' => 'decimal:2',
    ];
}

