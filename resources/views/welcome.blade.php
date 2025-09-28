@extends('base')

@section('title', 'Accueil')


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
@section('content')
    <section class="header">
        <h1 class="text-4xl font-extrabold text-blue-100 mb-6 tracking-wide drop-shadow-lg">What's in the box</h1>
        <form action="{{ route('home') }}" method="GET" class="flex items-center justify-center gap-3 mb-6">
            <select name="genre_id" onchange="this.form.submit()"
                class="pl-4 pr-8 py-2 rounded-lg border-2 border-blue-600 bg-white text-blue-900 font-semibold shadow focus:ring-2 focus:ring-blue-400 transition-all hover:border-blue-800">
                <option value="" class="bg-white text-blue-900" @if (!request('genre_id')) selected @endif>Tous
                    les genres</option>
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}
                        class="bg-white text-blue-900">
                        {{ $genre->name }} ({{ ($genre->movies_count ?? 0) + ($genre->shows_count ?? 0) }})
                    </option>
                @endforeach
            </select>
        </form>
    </section>

    <section class="section watchlist">
        <x-bladewind::tab name="indigo-tab" color="cyan">
            <x-slot name="headings">

                <x-bladewind::tab.heading name="cyan" active="true" label="Films" />

                <x-bladewind::tab.heading name="inactive-cyan" label="Séries" />

            </x-slot>

            <x-bladewind::tab.body>

                <x-bladewind::tab.content name="cyan" active="true">
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8 justify-items-center items-start mt-8">
                        @foreach ($movies_database as $movie)
                            <a href="{{ route('movies.show', ['id' => $movie->id]) }}" class="no-underline text-inherit">
                                @include('components.movie-card', [
                                    'movie' => $movie,
                                    'showAddButton' => false,
                                ])
                                <form action="{{ route('movies.deleteMovie', $movie->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_movie" value="{{ $movie->id }}">
                                    <button type="submit"
                                        class="mt-2 bg-red-600 text-white rounded-lg px-4 py-2 font-semibold cursor-pointer shadow hover:bg-red-700 focus:bg-red-800 focus:shadow-lg transition hover:scale-105 hover:ring-2 hover:ring-red-400 hover:ring-offset-2">
                                        Supprimer de ma liste
                                    </button>
                                </form>
                            </a>
                        @endforeach
                    </div>

                </x-bladewind::tab.content>


                <x-bladewind::tab.content name="inactive-cyan">
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8 justify-items-center items-start mt-8">
                        @foreach ($shows_database as $show)
                            <a href="{{ route('shows.show', ['id' => $show->id]) }}" class="no-underline text-inherit">
                                @include('components.show-card', [
                                    'show' => $show,
                                    'showAddButton' => false,
                                ])
                                <form action="{{ route('shows.deleteShow', $show->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_show" value="{{ $show->id }}">
                                    <button type="submit"
                                        class="mt-2 bg-red-600 text-white rounded-lg px-4 py-2 font-semibold cursor-pointer shadow hover:bg-red-700 focus:bg-red-800 focus:shadow-lg transition hover:scale-105 hover:ring-2 hover:ring-red-400 hover:ring-offset-2">
                                        Supprimer de ma liste
                                    </button>
                                </form>

                            </a>
                        @endforeach
                    </div>
                </x-bladewind::tab.content>
            </x-bladewind::tab.body>

        </x-bladewind::tab>

    </section>
@endsection
