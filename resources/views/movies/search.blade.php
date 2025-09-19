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

    @if ($movies_data->results)
        <section class="box_movies">
            @foreach ($movies_data->results as $movie)
                <article>
                    <div class="box_poster">
                        <img width="100%" src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_path }}"
                            alt="{{ $movie->display_name }}">
                    </div>
                    <h3>{{ $movie->display_name }}</h3>
                    <form action="{{ Route('movies.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                        <input type="submit" name="save_movie" value="Ajouter à ma liste">
                    </form>


                </article>
            @endforeach
        </section>
    @endif

@endsection
