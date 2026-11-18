<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Data pegawai & staf sekolah. Kunci = nomor induk: NIP (18 digit) atau NIK (16 digit).
        Schema::create('staffs', function (Blueprint $table) {
            $table->string('staff_number', 20)->primary();
            $table->string('name');
            $table->string('gender', 1)->nullable();             // L / P
            $table->string('religion', 30)->nullable();
            $table->string('position');                          // Jabatan: Guru, TU, dst.
            $table->string('employment_status', 50)->nullable(); // PNS, PPPK, PPPK PW, GTT - OUTS, dst.
            $table->string('nip', 30)->nullable();               // NIP berformat asli (dengan spasi)
            $table->string('rank_name')->nullable();             // Pangkat
            $table->string('grade', 20)->nullable();             // Golongan
            $table->string('unit')->nullable();                  // Mapel / bagian / unit kerja
            $table->string('phone', 30)->nullable();
            $table->string('note')->nullable();

            $table->string('activation_status', 20)->default('inactive')->index();
            $table->string('activation_password')->nullable();
            $table->timestamp('activation_requested_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->string('activation_note')->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('staff_number', 20)->nullable()->unique()->after('nis');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['staff_number']);
            $table->dropColumn('staff_number');
        });

        Schema::dropIfExists('staffs');
    }
};
