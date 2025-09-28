@props(['show', 'showRank' => false, 'rank' => null, 'showAddButton' => true])
@php
    $localImage = isset($show->id) ? Storage::url('poster/shows/' . $show->id . '.jpg') : null;
    $hasLocal = isset($show->id) && Storage::disk('public')->exists('poster/shows/' . $show->id . '.jpg');
    $tmdbImage = isset($show->poster_path) ? 'https://image.tmdb.org/t/p/w500' . $show->poster_path : null;
    $imageSrc = $hasLocal ? $localImage : $tmdbImage;
@endphp
<article
    class="relative rounded-2xl shadow-lg overflow-hidden max-h-[420px] h-[420px] aspect-[1/1.4] box-border hover:scale-105 hover:shadow-2xl transition-transform duration-200 group">
    @if ($imageSrc)
        <img src="{{ $imageSrc }}" alt="{{ $show->title ?? $show->name }}"
            class="absolute inset-0 w-full h-full object-cover z-0 transition duration-300 group-hover:scale-105" />
        <div class="absolute inset-0 bg-black/60 group-hover:bg-black/40 transition duration-300 z-10"></div>
    @else
        <div class="absolute inset-0 bg-gray-800 flex items-center justify-center z-0">
            <span class="text-gray-400">Aucune image</span>
        </div>
        <div class="absolute inset-0 bg-black/60 z-10"></div>
    @endif
    <div class="relative z-20 flex flex-col items-center justify-end h-full p-5 gap-3">
        @if ($showRank && $rank)
            <div class="absolute top-3 right-3 z-30 flex items-center gap-1">
                <span class="bg-white text-[#525b01] rounded-full px-3 py-1 font-bold shadow">{{ $rank }}</span>
            </div>
        @endif

        <div class="w-full flex flex-col items-start">
            <h3
                class="text-lg font-semibold text-left tracking-wide mt-2 mb-1 text-white drop-shadow-lg group-hover:drop-shadow-2xl transition duration-300">
                {{ $show->name }}</h3>
            <div class="flex items-center justify-between w-full mb-2 pr-1">
                <span class="text-sm text-left">
                    {{ isset($show->first_air_date) ? substr($show->first_air_date, 0, 4) : (isset($show['first_air_date']) ? substr($show['first_air_date'], 0, 4) : 'N/A') }}
                </span>
                <span
                    class="flex items-center justify-center rounded-full bg-white/70 backdrop-blur-md border border-blue-200 text-blue-700 font-bold text-xs w-10 h-10 shadow"
                    style="box-shadow: 0 2px 8px 0 rgba(0,0,0,0.08);">{{ round(($show->vote_average ?? $show['vote_average']) * 10) }}%</span>

            </div>

            {{-- @if (isset($show->genres))
                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach ($show->genres as $genre)
                        <x-bladewind::tag label="{{ $genre->name }}" shade="dark" color="cyan" tiny="true" />
                    @endforeach
                </div>
            @endif --}}
        </div>
        @if ($showAddButton)
            <form action="{{ route('shows.storeShow') }}" method="POST" class="w-full flex justify-center">
                @csrf
                <input type="hidden" name="show_id" value="{{ $show->id }}">
                <input type="submit" name="save_show" value="Ajouter à ma liste"
                    class="!bg-blue-600 text-white rounded-lg px-4 py-2 font-semibold cursor-pointer mt-2 shadow hover:bg-blue-700 focus:bg-blue-800 focus:shadow-lg transition group-hover:scale-105 group-hover:ring-2 group-hover:ring-blue-400 group-hover:ring-offset-2" />
            </form>
        @endif
    </div>
</article>
