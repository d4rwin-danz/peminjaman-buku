<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

/**
 * Mengisi data siswa dari DADIR kelas XII 2026/2027 (749 siswa, 22 kelas).
 * Aman dijalankan ulang: hanya memperbarui data induk, status aktivasi tidak disentuh.
 */
class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $rows = json_decode(
            file_get_contents(database_path('data/dadir_xii_2026_2027.json')),
            true
        );

        foreach ($rows as $row) {
            $nis = $row['nis'];
            unset($row['nis']);

            Student::updateOrCreate(
                ['nis' => $nis],
                $row + ['academic_year' => '2026/2027']
            );
        }
    }
}
