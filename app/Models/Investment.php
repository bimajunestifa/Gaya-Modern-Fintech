<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'asset_name',
        'asset_type',
        'principal_invested',
        'current_value',
        'return_percentage',
        'risk_level',
        'maturity_date',
    ];

    protected $casts = [
        'maturity_date' => 'date',
        'principal_invested' => 'decimal:2',
        'current_value' => 'decimal:2',
    ];
}

