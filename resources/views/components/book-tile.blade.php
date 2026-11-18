@props(['book'])

{{-- Tile buku: sampul besar tanpa bingkai kartu, info singkat di bawahnya. --}}
<a href="{{ route('siswa.books.show', $book) }}" class="group block">

    <div class="cover aspect-[3/4] overflow-hidden rounded-md rounded-l-sm bg-slate-200 shadow-md transition-shadow duration-200 group-hover:shadow-xl">
        @if ($book->cover)
            <img
                src="{{ asset(str_starts_with($book->cover, 'uploads/') ? $book->cover : 'storage/' . $book->cover) }}"
                alt="Sampul {{ $book->title }}"
                loading="lazy"
                class="h-full w-full object-cover"
            >
        @else
            {{-- Tanpa sampul: punggung buku bergaya katalog --}}
            <div class="flex h-full flex-col justify-between bg-ink-700 p-4 text-white">
                <p class="font-display text-base font-semibold leading-snug line-clamp-5">{{ $book->title }}</p>
                <p class="text-xs text-ink-200">{{ $book->author }}</p>
            </div>
        @endif
    </div>

    <h3 class="mt-3 line-clamp-2 font-semibold leading-snug text-slate-900 group-hover:text-ink-700">
        {{ $book->title }}
    </h3>

    <p class="mt-0.5 truncate text-sm text-slate-500">{{ $book->author }}</p>

    <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium {{ $book->stock > 0 ? 'text-green-700' : 'text-red-700' }}">
        <span class="h-1.5 w-1.5 rounded-full {{ $book->stock > 0 ? 'bg-green-600' : 'bg-red-600' }}"></span>
        {{ $book->stock > 0 ? $book->stock . ' tersedia' : 'Stok habis' }}
    </p>

</a>
