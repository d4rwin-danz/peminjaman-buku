<?php

use App\Models\Loan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kode unik peminjaman (dipakai untuk invoice & QR code).
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('loan_code', 30)
                ->nullable()
                ->unique()
                ->after('id');
        });

        // Isi kode untuk data peminjaman yang sudah ada.
        DB::table('loans')
            ->whereNull('loan_code')
            ->orderBy('id')
            ->get(['id', 'loan_date'])
            ->each(function ($row) {
                DB::table('loans')
                    ->where('id', $row->id)
                    ->update([
                        'loan_code' => Loan::generateUniqueCode($row->loan_date),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropUnique(['loan_code']);
            $table->dropColumn('loan_code');
        });
    }
};
