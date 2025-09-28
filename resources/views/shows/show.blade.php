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
                <div class="genre-labels">
                    @if ($show->seen === 1)
                        <x-bladewind::tag label="Vu" rounded="true" shade="dark" color="green" />
                    @endif
                    @foreach ($show->genres as $genre)
                        <x-bladewind::tag label="{{ $genre->name }}" rounded="true" />
                    @endforeach
                </div>
                <h2 class="movie-details-title">{{ $show->name }}</h2>
                <span class="movie-release-date">
                    {{ isset($show->first_air_date) ? substr($show->first_air_date, 0, 4) : (isset($show['first_air_date']) ? substr($show['first_air_date'], 0, 4) : 'N/A') }}
                </span>
                <p class="movie-details-description">{{ $show->description }}</p>
                <x-bladewind::progress-bar class="progress-bar-episode" percentage="{{ $percentageSeen }}"
                    show_percentage_label_inline="true" percentage_suffix="complete" show_percentage_label="true"
                    percentage_label_position="top center" />
            </div>
        </div>
        <div class="movie-details-extra">
            <div class="movie-actors-box">
                <h3>Acteurs principaux</h3>
                <div class="actors-list grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach ($mainActors as $actor)
                        <div class="actor-card flex flex-col items-center justify-center bg-white/70 rounded-xl shadow p-2">
                            @if ($actor->photo)
                                <img src="https://image.tmdb.org/t/p/w500{{ $actor->photo }}" alt="{{ $actor->name }}"
                                    class="actor-img w-28 h-28 sm:w-20 sm:h-20 object-cover rounded-full mb-2">
                            @endif
                            <div class="actor-info w-full text-center">
                                <span class="actor-name text-sm font-semibold text-blue-900">{{ $actor->name }}</span>
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
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mt-2 color-black">
                                @foreach ($episodes->where('season_number', $season->season_number) as $episode)
                                    <div
                                        class="episode-card relative w-full max-w-xs h-60 rounded-xl overflow-hidden shadow-xl bg-[#10243a] mx-auto">
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

                            function updateProgressBar(percentage) {
                                const progressBar = document.querySelector('.progress-bar-episode');
                                if (progressBar) {
                                    const bar = progressBar.querySelector('.bar-width');
                                    if (bar) {
                                        bar.style.width = percentage + '%';
                                    }
                                    const label = progressBar.querySelector('.bar-width span.opacity-100');
                                    if (label) {
                                        label.textContent = Math.round(percentage) + '%';
                                    }
                                }
                            }

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
                                                return;
                                            }
                                            if (data.percentage !== undefined) {
                                                updateProgressBar(data.percentage);
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
