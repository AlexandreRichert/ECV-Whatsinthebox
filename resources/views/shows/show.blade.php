@extends('base')

@section('title', $show->name . ' - Détails')

@section('content')
    <div class="movie-details-card">
        <div class="movie-details-main">
            <div class="movie-poster-box">
                @if ($posterPath)
                    <img src="{{ $posterPath }}" alt="Affiche du film" class="movie-poster-img">
                @endif
            </div>
            <div class="movie-info-box">
                <h2 class="movie-details-title">{{ $show->name }}</h2>
                <p class="movie-details-description">{{ $show->description }}</p>
                <x-bladewind::progress-bar percentage="{{ $percentageSeen }}" show_percentage_label_inline="true"
                    percentage_suffix="complete" show_percentage_label="true" percentage_label_position="top center" />
            </div>
        </div>
        <div class="movie-details-extra">
            <div class="movie-actors-box">
                <h3>Acteurs principaux</h3>
                <div class="actors-list">
                    @foreach ($mainActors as $actor)
                        <div class="actor-card">
                            @if ($actor->photo)
                                <img src="https://image.tmdb.org/t/p/w500{{ $actor->photo }}" alt="{{ $actor->name }}"
                                    class="actor-img">
                            @endif
                            <div class="actor-info">
                                <span class="actor-name">{{ $actor->name }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="movie-seasons-box">
                <div class="seasons-list">
                    <div class="flex flex-wrap items-center gap-4">
                        <h3 class="text-xl font-bold text-[#10243a]">Episodes</h3>
                        <select name="season" id="season-select"
                            class="pl-4 pr-8 py-2 rounded-lg border-2 border-blue-600 bg-white text-blue-900 font-semibold shadow focus:ring-2 focus:ring-blue-400 transition-all hover:border-blue-800">
                            @foreach ($seasons as $season)
                                <option value="{{ $season->season_number }}"
                                    @if ($season->season_number == 1) selected @endif>
                                    Saison {{ $season->season_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    @foreach ($seasons as $season)
                        <div class="season-card" data-season="{{ $season->season_number }}" style="display:none;">
                            <div class="flex flex-wrap gap-4 mt-2 color-black justify-between">
                                @foreach ($episodes->where('season_number', $season->season_number) as $episode)
                                    <div
                                        class="episode-card relative w-90 h-60 rounded-xl overflow-hidden shadow-xl bg-[#10243a]">
                                        <img src="https://image.tmdb.org/t/p/w500{{ $episode->image }}"
                                            alt="{{ $episode->name }}"
                                            class="absolute inset-0 w-full h-full object-cover brightness-80 saturate-140">
                                        <div class="absolute top-3 right-3 z-10">
                                            <input type="checkbox"
                                                class="accent-green-600 w-6 h-6 rounded-full border-2 border-green-600 focus:ring-2 focus:ring-green-400 transition-all"
                                                data-episode-id="{{ $episode->id }}"
                                                @if ($episode->seen) checked @endif aria-label="Épisode vu" />
                                        </div>
                                        <div
                                            class="absolute bottom-0 left-0 w-full px-4 py-3 flex flex-col items-start bg-gradient-to-t from-[#193a5e]/90 to-[#10243a]/90">
                                            <span class="text-lg font-bold text-white drop-shadow-lg">Episode
                                                {{ $episode->episode_number }}</span>
                                            <span
                                                class="text-base font-semibold text-white drop-shadow-lg">{{ $episode->name }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const select = document.getElementById('season-select');
                            const seasonCards = document.querySelectorAll('.season-card');

                            function showSelectedSeason() {
                                seasonCards.forEach(card => {
                                    card.style.display = (select.value && card.getAttribute('data-season') === select
                                        .value) ? 'block' : 'none';
                                });
                            }
                            select.addEventListener('change', showSelectedSeason);
                            select.value = '1';
                            showSelectedSeason();

                            document.querySelectorAll('input[type="checkbox"][data-episode-id]').forEach(function(checkbox) {
                                checkbox.addEventListener('change', function() {
                                    fetch(`/shows/episode/${this.dataset.episodeId}/seen`, {
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
                </div>
            </div>
        </div>
    @endsection
