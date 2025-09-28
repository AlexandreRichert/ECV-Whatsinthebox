@props(['movie', 'showRank' => false, 'rank' => null, 'showAddButton' => true, 'showSeenCheckbox' => false])
@php
    $localImage = isset($movie->id) ? Storage::url('poster/movies/' . $movie->id . '.jpg') : null;
    $hasLocal = isset($movie->id) && Storage::disk('public')->exists('poster/movies/' . $movie->id . '.jpg');
    $tmdbImage = isset($movie->poster_path) ? 'https://image.tmdb.org/t/p/w500' . $movie->poster_path : null;
    $imageSrc = $hasLocal ? $localImage : $tmdbImage;
@endphp
<article
    class="relative rounded-2xl shadow-lg overflow-hidden max-h-[420px] h-[420px] aspect-[1/1.4] box-border hover:scale-105 hover:shadow-2xl transition-transform duration-200 group">
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
        <div class="w-full flex flex-col items-start">
            @if ($showSeenCheckbox)
                <div class="absolute top-3 right-3 z-10">
                    <input type="checkbox"
                        class="accent-green-600 w-6 h-6 rounded-full border-2 border-green-600 focus:ring-2 focus:ring-green-400 transition-all movie-seen-checkbox"
                        data-movie-id="{{ $movie->id }}" @if ($movie->seen) checked @endif
                        aria-label="Film vu" />
                </div>
            @endif

            <h3
                class="text-lg font-semibold text-left tracking-wide mt-2 mb-1 text-white drop-shadow-lg group-hover:drop-shadow-2xl transition duration-300">
                {{ $movie->name ?? ($movie->title ?? 'Film inconnu') }}</h3>
            <div class="flex items-center justify-between w-full mb-2 pr-1">
                <span
                    class="text-sm text-left font-semibold">{{ isset($movie->release_date) ? substr($movie->release_date, 0, 4) : (isset($movie['release_date']) ? substr($movie['released_date'], 0, 4) : 'N/A') }}</span>
                <span
                    class="flex items-center justify-center rounded-full bg-white/70 backdrop-blur-md border border-blue-200 text-blue-700 font-bold text-xs w-10 h-10 shadow"
                    style="box-shadow: 0 2px 8px 0 rgba(0,0,0,0.08);">
                    {{ round(($movie->vote_average ?? $movie['vote_average']) * 10) }}%
                </span>
            </div>
        </div>
        @if ($showAddButton)
            <form action="{{ route('movies.store') }}" method="POST" class="w-full flex justify-center">
                @csrf
                <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                <input type="submit" name="save_movie" value="Ajouter à ma liste"
                    class="!bg-blue-600 text-white rounded-lg px-4 py-2 font-semibold cursor-pointer mt-2 shadow hover:bg-blue-700 focus:bg-blue-800 focus:shadow-lg transition group-hover:scale-105 group-hover:ring-2 group-hover:ring-blue-400 group-hover:ring-offset-2" />
            </form>
        @endif
    </div>
</article>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.movie-seen-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                fetch(`/movies/${this.dataset.movieId}/seen`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            seen: this.checked ? 1 : 0
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Erreur lors de la mise à jour.');
                        }
                    })
                    .catch(() => alert('Erreur lors de la mise à jour.'));
            });
        });
    });
</script>
