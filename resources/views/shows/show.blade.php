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
        </div>
    </div>
@endsection
