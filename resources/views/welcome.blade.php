@extends('base')

@section('title', 'Accueil')

@section('content')
    <section class="header">
        <h1>What's in the box</h1>
        <form action="{{ route('home') }}" method="GET">
            <select name="genre_id" onchange="this.form.submit()">
                <option value="">Tous les genres</option>
                @if (isset($genres) && count($genres))
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}" @if (request('genre_id') == $genre->id) selected @endif>
                            {{ $genre->name }} ({{ $genre->movies_count ?? 0 }})
                        </option>
                    @endforeach
                @endif
            </select>
        </form>
    </section>

    <section class="section watchlist">
        <div class="box_movies">
            @foreach ($movies_database as $movie)
                <a href="{{ route('movies.show', ['id' => $movie->id]) }}" style="text-decoration: none; color: inherit;">
                    <article>
                        <div class="box_poster">
                            <img class="" src="{{ Storage::url('poster/' . $movie->id . '.jpg') }}"
                                alt="{{ $movie->name }}">
                        </div>
                        <h3>{{ $movie->name }}</h3>
                        <p>
                            @foreach ($movie->genres as $index => $genre)
                                {{ $genre->name }}@if ($index < count($movie->genres) - 1)
                                    /
                                @endif
                            @endforeach
                        </p>
                    </article>
                </a>
            @endforeach

        </div>
    </section>
@endsection
