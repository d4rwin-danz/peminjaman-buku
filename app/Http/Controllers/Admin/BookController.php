<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    private function rackLocations(): array
    {
        return [
            'utara_1' => 'Rak Utara 1',
            'utara_2' => 'Rak Utara 2',
            'utara_3' => 'Rak Utara 3',
            'utara_4' => 'Rak Utara 4',
            'utara_5' => 'Rak Utara 5',
            'utara_6' => 'Rak Utara 6',

            'barat_1' => 'Rak Barat 1',
            'barat_2' => 'Rak Barat 2',
            'barat_3' => 'Rak Barat 3',
            'barat_4' => 'Rak Barat 4',
            'barat_5' => 'Rak Barat 5',
            'barat_6' => 'Rak Barat 6',

            'selatan_1' => 'Rak Selatan 1',
            'selatan_2' => 'Rak Selatan 2',
        ];
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category');

        $books = Book::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.books.index', compact(
            'books',
            'categories',
            'search',
            'categoryId'
        ));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $rackLocations = $this->rackLocations();

        return view('admin.books.create', compact(
            'categories',
            'rackLocations'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:books,code',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'author' => [
                'required',
                'string',
                'max:255',
            ],
            'publisher' => [
                'nullable',
                'string',
                'max:255',
            ],
            'publication_year' => [
                'nullable',
                'integer',
                'min:1000',
                'max:2100',
            ],
            'isbn' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'rack_location' => [
                'nullable',
                'string',
                Rule::in(array_keys($this->rackLocations())),
            ],
            'cover' => [
                'nullable',
                'image',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('cover')) {
            $destination = public_path('uploads/covers');

            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $file = $request->file('cover');
            $filename = Str::uuid() . '.' . $file->extension();

            $file->move($destination, $filename);

            $validated['cover'] = 'uploads/covers/' . $filename;
        }

        Book::create($validated);

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        $book->load('category');

        $rackLocations = $this->rackLocations();

        // Statistik peminjaman buku ini (di luar pengajuan yang ditolak).
        $details = $book->loanDetails()
            ->whereHas('loan', fn ($q) => $q->where('status', '!=', 'rejected'));

        $loanStats = [
            'total' => (clone $details)->count(),
            'copies' => (int) (clone $details)->sum('quantity'),
            'active' => (clone $details)
                ->whereHas('loan', fn ($q) => $q->whereIn('status', ['approved', 'borrowed', 'return_pending']))
                ->count(),
        ];

        $recentLoans = $book->loanDetails()
            ->with('loan.user')
            ->whereHas('loan', fn ($q) => $q->where('status', '!=', 'rejected'))
            ->latest('id')
            ->limit(5)
            ->get();

        return view('admin.books.show', compact(
            'book',
            'rackLocations',
            'loanStats',
            'recentLoans'
        ));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        $rackLocations = $this->rackLocations();

        return view('admin.books.edit', compact(
            'book',
            'categories',
            'rackLocations'
        ));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('books', 'code')->ignore($book->id),
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'author' => [
                'required',
                'string',
                'max:255',
            ],
            'publisher' => [
                'nullable',
                'string',
                'max:255',
            ],
            'publication_year' => [
                'nullable',
                'integer',
                'min:1000',
                'max:2100',
            ],
            'isbn' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'rack_location' => [
                'nullable',
                'string',
                Rule::in(array_keys($this->rackLocations())),
            ],
            'cover' => [
                'nullable',
                'image',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('cover')) {
            $destination = public_path('uploads/covers');

            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            if (
                $book->cover &&
                str_starts_with($book->cover, 'uploads/covers/')
            ) {
                $oldCover = public_path($book->cover);

                if (File::exists($oldCover)) {
                    File::delete($oldCover);
                }
            }

            $file = $request->file('cover');
            $filename = Str::uuid() . '.' . $file->extension();

            $file->move($destination, $filename);

            $validated['cover'] = 'uploads/covers/' . $filename;
        }

        $book->update($validated);

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if (
            $book->cover &&
            str_starts_with($book->cover, 'uploads/covers/')
        ) {
            $coverPath = public_path($book->cover);

            if (File::exists($coverPath)) {
                File::delete($coverPath);
            }
        }

        $book->delete();

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}