<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Data induk siswa (isi DADIR). NIS adalah primary key.
        Schema::create('students', function (Blueprint $table) {
            $table->string('nis', 20)->primary();
            $table->string('name');
            $table->string('gender', 1)->nullable();          // L / P
            $table->string('religion', 30)->nullable();
            $table->string('class_name', 30)->index();        // contoh: XII RPL A
            $table->string('field_of_study')->nullable();     // Bidang Keahlian
            $table->string('study_program')->nullable();      // Program Keahlian
            $table->string('concentration')->nullable();      // Konsentrasi Keahlian
            $table->string('homeroom_teacher')->nullable();   // Wali Kelas
            $table->string('academic_year', 20)->default('2026/2027');

            // inactive = belum aktivasi, pending = menunggu ACC admin,
            // active = boleh login, rejected = ditolak admin
            $table->string('activation_status', 20)->default('inactive')->index();
            $table->string('activation_password')->nullable(); // hash, dihapus setelah ACC
            $table->timestamp('activation_requested_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->string('activation_note')->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // Login siswa memakai NIS; admin tetap memakai email.
        Schema::table('users', function (Blueprint $table) {
            $table->string('nis', 20)->nullable()->unique()->after('email');
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nis']);
            $table->dropColumn('nis');
        });

        Schema::dropIfExists('students');
    }
};
