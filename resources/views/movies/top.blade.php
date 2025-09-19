@extends('base')

@section('title', 'Films les mieux notés')

@section('content')
    <h2>Films les mieux notés</h2>
    @if (session('status'))
        <div class="toast-alert success">
            {{ session('status') }}
        </div>
        <script>
            setTimeout(function() {
                var toast = document.querySelector('.toast-alert');
                if (toast) toast.style.opacity = '0';
            }, 5000);
        </script>
    @endif
    @if (session('alert'))
        <div class="toast-alert warning">
            {{ session('alert') }}
        </div>
        <script>
            setTimeout(function() {
                var alert = document.querySelector('.toast-alert.warning');
                if (alert) alert.style.opacity = '0';
            }, 3500);
        </script>
    @endif
    <section class="section-top10">
        <h2>Top 10</h2>
        <div class="box_movies">
            @foreach (array_slice($movies_datas, 0, 10) as $index => $movie)
                <article class="card_movie card_top10">
                    <div class="card_movie_corner">
                        @if ($index === 0)
                            <span class="crown gold">&#x1F451;</span>
                        @elseif ($index === 1)
                            <span class="crown silver">&#x1F948;</span>
                        @elseif ($index === 2)
                            <span class="crown bronze">&#x1F949;</span>
                        @elseif ($index > 2)
                            <span class="rank">{{ $index + 1 }}</span>
                        @endif
                    </div>
                    <div class="box_poster">
                        <img class="card_movie_img"
                            src="https://image.tmdb.org/t/p/w500{{ $movie->poster_path ?? $movie['poster_path'] }}"
                            alt="Affiche {{ $movie->title ?? $movie['title'] }}">
                    </div>
                    <h3>{{ $movie->title ?? $movie['title'] }}</h3>
                    {{-- <p>{{ round(($movie->vote_average ?? $movie['vote_average']) * 10) }}%</p> --}}
                    <p class="card_movie_date">Sortie :
                        {{ isset($movie->release_date) ? substr($movie->release_date, 0, 4) : (isset($movie['release_date']) ? substr($movie['release_date'], 0, 4) : 'N/A') }}
                    </p>
                    <form action="{{ route('movies.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie->id ?? $movie['id'] }}">
                        <input type="submit" name="save_movie" value="Ajouter à ma liste">
                    </form>
                </article>
            @endforeach
        </div>
    </section>
    <section class="section top">
        <div class="box_movies">
            @foreach (array_slice($movies_datas, 10) as $index => $movie)
                <article class="card_movie">
                    <div class="card_movie_corner">
                        <span class="rank">{{ $index + 11 }}</span>
                    </div>
                    <div class="box_poster">
                        <img class="card_movie_img"
                            src="https://image.tmdb.org/t/p/w500{{ $movie->poster_path ?? $movie['poster_path'] }}"
                            alt="Affiche {{ $movie->title ?? $movie['title'] }}">
                    </div>
                    <h3>{{ $movie->title ?? $movie['title'] }}</h3>
                    <p>{{ round(($movie->vote_average ?? $movie['vote_average']) * 10) }}%</p>
                    <p class="card_movie_date">Sortie :
                        {{ isset($movie->release_date) ? substr($movie->release_date, 0, 4) : (isset($movie['release_date']) ? substr($movie['release_date'], 0, 4) : 'N/A') }}
                    </p>
                    <form action="{{ route('movies.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie->id ?? $movie['id'] }}">
                        <input type="submit" name="save_movie" value="Ajouter à ma liste">
                    </form>
                </article>
            @endforeach

        </div>
    </section>

@endsection
