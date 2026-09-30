<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE loans
            MODIFY COLUMN status ENUM(
                'pending',
                'approved',
                'rejected',
                'borrowed',
                'return_pending',
                'returned'
            )
            NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE loans
            MODIFY COLUMN status ENUM(
                'pending',
                'approved',
                'rejected',
                'borrowed',
                'returned'
            )
            NOT NULL DEFAULT 'pending'
        ");
    }
};