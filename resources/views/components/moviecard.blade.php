@props(['movie', 'showRank' => false, 'rank' => null])

<article
    class="relative rounded-2xl shadow-lg overflow-hidden max-h-[420px] h-[420px] aspect-[1/1.4] box-border hover:scale-105 hover:shadow-2xl transition-transform duration-200 group">
    @php
        $localImage = isset($movie->id) ? Storage::url('poster/movies/' . $movie->id . '.jpg') : null;
        $hasLocal = isset($movie->id) && Storage::disk('public')->exists('poster/movies/' . $movie->id . '.jpg');
        $tmdbImage = isset($movie->poster_path) ? 'https://image.tmdb.org/t/p/w500' . $movie->poster_path : null;
        $imageSrc = $hasLocal ? $localImage : $tmdbImage;
    @endphp

    @if ($imageSrc)
        <img src="{{ $imageSrc }}" alt="{{ $movie->title ?? $movie->name }}"
            class="absolute inset-0 w-full h-full object-cover z-0 transition duration-300 group-hover:scale-105" />
        <div class="absolute inset-0 bg-black/60 group-hover:bg-black/40 transition duration-300 z-10"></div>
    @else
        <div class="absolute inset-0 bg-gray-800 flex items-center justify-center z-0">
            <span class="text-gray-400">Aucune image</span>
        </div>
        <div class="absolute inset-0 bg-black/60 z-10"></div>
    @endif

    <div class="relative z-20 flex flex-col items-start justify-end h-full pl-5 pb-1 gap-3">
        @if ($showRank && $rank)
            <div class="absolute top-3 right-3 z-30 flex items-center gap-1">
                <span class="bg-white text-[#525b01] rounded-full px-3 py-1 font-bold shadow">{{ $rank }}</span>
            </div>
        @endif

        <h3
            class="text-lg font-semibold text-left tracking-wide mt-2 mb-1 text-white drop-shadow-lg group-hover:drop-shadow-2xl transition duration-300">
            {{ $movie->name ?? ($movie->title ?? 'Film inconnu') }}
        </h3>

        <div class="flex items-center justify-between w-full mb-2 pr-1">
            <span class="text-sm text-left font-semibold">
                {{ $releaseYear }}
            </span>
            <span
                class="flex items-center justify-center rounded-full bg-white/70 backdrop-blur-md border border-blue-200 text-blue-700 font-bold text-xs w-10 h-10 shadow">
                {{ $percentageAverageNote }}
            </span>
        </div>

        <!-- Slot pour les boutons -->
        <div class="w-full flex justify-center">
            {{ $slot }}
        </div>
    </div>
</article>
