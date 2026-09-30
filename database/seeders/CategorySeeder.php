<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fiksi',
                'description' => 'Buku cerita, novel, dan karya sastra fiksi.',
            ],
            [
                'name' => 'Teknologi',
                'description' => 'Buku tentang teknologi, komputer, pemrograman, dan informatika.',
            ],
            [
                'name' => 'Pendidikan',
                'description' => 'Buku pembelajaran dan referensi pendidikan.',
            ],
            [
                'name' => 'Sains',
                'description' => 'Buku ilmu pengetahuan dan sains.',
            ],
            [
                'name' => 'Sejarah',
                'description' => 'Buku sejarah Indonesia dan dunia.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}