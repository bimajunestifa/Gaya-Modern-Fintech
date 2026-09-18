<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_number',
        'borrower_name',
        'loan_type',
        'principal_amount',
        'interest_rate',
        'tenor_months',
        'monthly_installment',
        'remaining_balance',
        'npl_status',
        'disbursed_at',
        'due_date',
    ];

    protected $casts = [
        'disbursed_at' => 'date',
        'due_date' => 'date',
        'principal_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
    ];
}

