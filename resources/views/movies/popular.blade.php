@extends('base')

@section('title', 'Films Populaire')

@section('content')
    <h2>Films Populaires</h2>

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
            }, 5000);
        </script>
    @endif

    <section class="box_movies">
        @foreach ($movies_datas->results as $movie)
            <article>
                <div class="box_poster">
                    <img class="" src="https://image.tmdb.org/t/p/w500{{ $movie->poster_path }}" alt="">
                </div>
                <h2>{{ $movie->title }}</h2>
                <form action="{{ route('movies.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                    <input type="submit" name="save_movie" value="Ajouter à ma liste">
                </form>
            </article>
        @endforeach
    </section>
@endsection
