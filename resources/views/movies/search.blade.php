@extends('base')

@section('title', 'Film Populaire')

@section('content')
    <h2>Résultats de la recherche</h2>

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

    <div class="search-results-wrapper">
        <section class="box_movies_wrapp">
            <h2>Films</h2>
            <div class="box_movies">
                @foreach ($movies_data->results ?? [] as $result)
                    @if ($result->media_type === 'movie')
                        <article>
                            @if (!empty($result->poster_path))
                                <div class="box_poster">

                                    <img src="https://image.tmdb.org/t/p/w500{{ $result->poster_path }}"
                                        alt="{{ $result->display_name }}">
                                </div>
                            @else
                                <div class="search-result-no-poster">Aucune image</div>
                            @endif
                            <h3>{{ $result->display_name }}</h3>
                            <form action="{{ Route('movies.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="movie_id" value="{{ $result->id }}">
                                <input type="submit" name="save_movie" value="Ajouter à ma liste">
                            </form>

                        </article>
                    @endif
                @endforeach
            </div>

        </section>
        <section class="box_movies_wrapp">
            <h2>Séries</h2>
            <div class="box_movies">
                @foreach ($movies_data->results ?? [] as $result)
                    @if ($result->media_type === 'tv')
                        <article>
                            @if (!empty($result->poster_path))
                                <div class="box_poster">

                                    <img src="https://image.tmdb.org/t/p/w500{{ $result->poster_path }}"
                                        alt="{{ $result->display_name }}">
                                </div>
                            @else
                                <div class="search-result-no-poster">Aucune image</div>
                            @endif
                            <h3>{{ $result->display_name }}</h3>
                            <form action="{{ Route('movies.storeShow') }}" method="POST">
                                @csrf
                                <input type="hidden" name="show_id" value="{{ $result->id }}">
                                <input type="submit" name="save_show" value="Ajouter à ma liste">
                            </form>

                        </article>
                    @endif
                @endforeach
            </div>
        </section>
    </div>
@endsection
