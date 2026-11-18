<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Dashboard publik (untuk pengunjung yang belum login).
     * User yang sudah login langsung diarahkan ke dashboard sesuai role.
     */
    public function index()
    {
        if (auth()->check()) {
            return redirect()->route(
                auth()->user()->role === 'admin'
                    ? 'admin.dashboard'
                    : 'siswa.dashboard'
            );
        }

        $totalJudul = Book::count();
        $totalStok = (int) Book::sum('stock');
        $totalKategori = Category::count();

        $categories = Category::withCount('books')
            ->orderBy('name')
            ->get();

        // Rak di hero: buku terbaru, tampil sebagai punggung buku.
        $shelfBooks = Book::latest()->take(14)->get();

        // Pilihan acak (yang stoknya ada) supaya beranda terasa berbeda tiap dibuka.
        $picks = Book::with('category')
            ->where('stock', '>', 0)
            ->whereNotIn('id', $shelfBooks->pluck('id'))
            ->inRandomOrder()
            ->take(8)
            ->get();

        if ($picks->isEmpty()) {
            $picks = Book::with('category')
                ->where('stock', '>', 0)
                ->latest()
                ->take(8)
                ->get();
        }

        return view('home', compact(
            'totalJudul',
            'totalStok',
            'totalKategori',
            'categories',
            'shelfBooks',
            'picks'
        ));
    }
}
