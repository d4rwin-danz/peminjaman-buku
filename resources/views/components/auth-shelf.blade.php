@props(['title', 'text'])

{{-- Panel kiri halaman login/daftar: rak buku statis + satu kalimat. --}}
<div class="relative hidden flex-col justify-between overflow-hidden bg-ink-900 p-8 text-white md:col-span-2 md:flex" style="min-height: 420px">

    <div>
        <h2 class="font-display text-2xl font-semibold leading-snug">{{ $title }}</h2>
        <p class="mt-3 text-sm leading-relaxed text-ink-200">{{ $text }}</p>
    </div>

    <div class="-mx-8 -mb-8">
        <div class="flex items-end gap-1.5 px-8">
            @foreach ([[150,'#E2B13C'],[190,'#C3CFE3'],[130,'#2F7A78'],[210,'#6B87B5'],[160,'#8A4B6F'],[180,'#F1F4F9'],[140,'#4F8A5B']] as $i => [$tinggi, $warna])
                <span
                    class="spine"
                    style="--i: {{ $i }}; height: {{ $tinggi }}px; width: {{ 30 + ($i * 7) % 18 }}px; background: {{ $warna }}; pointer-events: none"
                    aria-hidden="true"
                ></span>
            @endforeach
        </div>
        <div class="h-3 border-t-4 border-marker bg-ink-950"></div>
    </div>

</div>
