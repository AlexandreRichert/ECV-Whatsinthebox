@extends('base')

@section('title', 'Accueil')

@section('content')
    <section class="header">
        <h1 class="text-4xl font-extrabold text-blue-100 mb-6 tracking-wide drop-shadow-lg">What's in the box</h1>
        @include('components.genre-select', ['genres' => $genres, 'selected' => request('genre_id')])
    </section>

    <section class="section watchlist">
        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8 justify-items-center items-start mt-8">
            @foreach ($movies_database as $movie)
                <a href="{{ route('movies.show', ['id' => $movie->id]) }}" class="no-underline text-inherit">
                    @include('components.movie-card', ['movie' => $movie])
                </a>
            @endforeach
        </div>
    </section>
@endsection
