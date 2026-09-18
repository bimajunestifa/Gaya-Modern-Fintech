<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_account_id',
        'card_holder',
        'card_number',
        'card_type',
        'tier',
        'valid_thru',
        'balance',
        'theme_style',
        'is_active',
    ];

    public function account()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }
}

