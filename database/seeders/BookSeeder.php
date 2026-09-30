<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'code' => 'BK-001',
                'title' => 'Dasar-Dasar Pemrograman',
                'author' => 'Abdul Kadir',
                'publisher' => 'Andi',
                'publication_year' => 2024,
                'isbn' => '9780000000001',
                'stock' => 5,
                'category' => 'Teknologi',
                'description' => 'Buku pengantar dasar pemrograman untuk pemula.',
            ],
            [
                'code' => 'BK-002',
                'title' => 'Pemrograman Web dengan Laravel',
                'author' => 'Muhammad Arif',
                'publisher' => 'Informatika',
                'publication_year' => 2024,
                'isbn' => '9780000000002',
                'stock' => 5,
                'category' => 'Teknologi',
                'description' => 'Panduan membangun aplikasi web menggunakan Laravel.',
            ],
            [
                'code' => 'BK-003',
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'publication_year' => 2005,
                'isbn' => '9780000000003',
                'stock' => 4,
                'category' => 'Fiksi',
                'description' => 'Novel tentang perjuangan dan pendidikan.',
            ],
            [
                'code' => 'BK-004',
                'title' => 'Ensiklopedia Sains',
                'author' => 'Tim Edukasi',
                'publisher' => 'Erlangga',
                'publication_year' => 2023,
                'isbn' => '9780000000004',
                'stock' => 3,
                'category' => 'Sains',
                'description' => 'Buku pengetahuan dasar mengenai ilmu pengetahuan.',
            ],
            [
                'code' => 'BK-005',
                'title' => 'Sejarah Indonesia',
                'author' => 'Ricklefs',
                'publisher' => 'Serambi',
                'publication_year' => 2022,
                'isbn' => '9780000000005',
                'stock' => 4,
                'category' => 'Sejarah',
                'description' => 'Pembahasan mengenai perjalanan sejarah Indonesia.',
            ],
            [
                'code' => 'BK-006',
                'title' => 'Strategi Belajar Efektif',
                'author' => 'Budi Santoso',
                'publisher' => 'Gramedia',
                'publication_year' => 2024,
                'isbn' => '9780000000006',
                'stock' => 5,
                'category' => 'Pendidikan',
                'description' => 'Panduan untuk meningkatkan efektivitas belajar siswa.',
            ],
        ];

        foreach ($books as $book) {
            $category = Category::where('name', $book['category'])->first();

            Book::updateOrCreate(
                [
                    'code' => $book['code'],
                ],
                [
                    'category_id' => $category->id,
                    'title' => $book['title'],
                    'author' => $book['author'],
                    'publisher' => $book['publisher'],
                    'publication_year' => $book['publication_year'],
                    'isbn' => $book['isbn'],
                    'stock' => $book['stock'],
                    'description' => $book['description'],
                ]
            );
        }
    }
}