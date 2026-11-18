<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

/**
 * Mengisi data guru dari "DATA KEPEGAWAIAN 2026" (update Juli 2026): 113 orang ber-NIP.
 * Aman dijalankan ulang: status aktivasi tidak disentuh.
 */
class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $rows = json_decode(
            file_get_contents(database_path('data/pegawai_2026.json')),
            true
        );

        foreach ($rows as $row) {
            $number = $row['staff_number'];
            unset($row['staff_number']);

            Staff::updateOrCreate(['staff_number' => $number], $row);
        }
    }
}
