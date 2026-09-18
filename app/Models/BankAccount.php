<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'account_name',
        'account_type',
        'currency',
        'balance',
        'status',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function cards()
    {
        return $this->hasMany(BankCard::class);
    }
}

