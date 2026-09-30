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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            // Siswa yang melakukan peminjaman
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();

            /*
             * Status transaksi:
             * pending  = menunggu persetujuan admin
             * approved = disetujui admin
             * rejected = ditolak admin
             * borrowed = buku sedang dipinjam
             * returned = buku sudah dikembalikan
             */
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'borrowed',
                'returned',
            ])->default('pending');

            // Bukti foto saat peminjaman
            $table->string('borrowing_proof')->nullable();

            // Bukti foto saat pengembalian
            $table->string('return_proof')->nullable();

            // Jumlah hari keterlambatan
            $table->unsignedInteger('late_days')->default(0);

            // Denda
            $table->unsignedBigInteger('fine')->default(0);

            // Catatan tambahan
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};