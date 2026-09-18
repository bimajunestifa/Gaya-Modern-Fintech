<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psak413JournalEntry extends Model
{
    use HasFactory;

    protected $table = 'psak413_journal_entries';

    protected $fillable = [
        'entry_number',
        'entry_date',
        'account_code',
        'account_name',
        'contract_type',
        'debit',
        'credit',
        'description',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];
}

