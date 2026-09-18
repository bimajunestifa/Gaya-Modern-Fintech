<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('psak_journal_entries', function (Blueprint $table) {
            $table->dropUnique(['entry_number']);
            $table->index('entry_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psak_journal_entries', function (Blueprint $table) {
            $table->dropIndex(['entry_number']);
            $table->unique('entry_number');
        });
    }
};
