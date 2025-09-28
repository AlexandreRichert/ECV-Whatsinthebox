@extends('base')

@section('title', 'Film Populaire')

@section('content')
    <h2 class="text-3xl text-center font-bold text-white !m-16 drop-shadow-lg">Résultats de la recherche</h2>

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
    <x-bladewind::tab name="indigo-tab" color="cyan">
        <x-slot name="headings">

            <x-bladewind::tab.heading name="cyan" active="true" label="Films" />

            <x-bladewind::tab.heading name="inactive-cyan" label="Séries" />

        </x-slot>

        <x-bladewind::tab.body>

            <x-bladewind::tab.content name="cyan" active="true">
                <section class="box_movies_wrapp">
                    <h2 class="text-2xl font-bold text-white mb-6 drop-shadow-lg">Films</h2>
                    <div
                        class="grid gap-6 sm:gap-4 justify-items-center items-start grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                        @foreach ($movies_data->results ?? [] as $result)
                            @if ($result->media_type === 'movie')
                                <article
                                    class="relative rounded-2xl shadow-lg overflow-hidden max-h-[420px] h-[420px] aspect-[1/1.4] box-border hover:scale-105 hover:shadow-2xl transition-transform duration-200 group flex flex-col items-center justify-between p-5 gap-3">
                                    @if (!empty($result->poster_path))
                                        <img src="https://image.tmdb.org/t/p/w500{{ $result->poster_path }}"
                                            alt="{{ $result->display_name }}"
                                            class="absolute inset-0 w-full h-full object-cover z-0 transition duration-300 group-hover:scale-105" />
                                        <div
                                            class="absolute inset-0 bg-black/60 group-hover:bg-black/40 transition duration-300 z-10">
                                        </div>
                                    @else
                                        <div class="absolute inset-0 bg-gray-800 flex items-center justify-center z-0">
                                            <span class="text-gray-400">Aucune image</span>
                                        </div>
                                        <div class="absolute inset-0 bg-black/60 z-10"></div>
                                    @endif
                                    <div class="relative z-20 w-full flex flex-col items-center justify-end h-full">
                                        <h3
                                            class="text-lg font-semibold text-center tracking-wide mt-2 mb-1 text-white drop-shadow-lg group-hover:drop-shadow-2xl transition duration-300">
                                            {{ $result->display_name }}</h3>
                                        <form action="{{ Route('movies.store') }}" method="POST"
                                            class="w-full flex justify-center">
                                            @csrf
                                            <input type="hidden" name="movie_id" value="{{ $result->id }}">
                                            <input type="submit" name="save_movie" value="Ajouter à ma liste"
                                                class="!bg-blue-600 text-white rounded-lg px-4 py-2 font-semibold cursor-pointer mt-2 shadow !hover:bg-blue-700 !focus:bg-blue-800 !focus:shadow-lg !transition group-hover:scale-105 group-hover:ring-2 group-hover:ring-blue-400 group-hover:ring-offset-2" />
                                        </form>
                                    </div>
                                </article>
                            @endif
                        @endforeach
                    </div>

                </section>

            </x-bladewind::tab.content>


            <x-bladewind::tab.content name="inactive-cyan">
                <section class="box_movies_wrapp">
                    <h2 class="text-2xl font-bold text-white mb-6 drop-shadow-lg">Séries</h2>
                    <div
                        class="grid gap-6 sm:gap-4 justify-items-center items-start grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                        @foreach ($movies_data->results ?? [] as $result)
                            @if ($result->media_type === 'tv')
                                <article
                                    class="relative rounded-2xl shadow-lg overflow-hidden max-h-[420px] h-[420px] aspect-[1/1.4] box-border hover:scale-105 hover:shadow-2xl transition-transform duration-200 group flex flex-col items-center justify-between p-5 gap-3">
                                    @if (!empty($result->poster_path))
                                        <img src="https://image.tmdb.org/t/p/w500{{ $result->poster_path }}"
                                            alt="{{ $result->display_name }}"
                                            class="absolute inset-0 w-full h-full object-cover z-0 transition duration-300 group-hover:scale-105" />
                                        <div
                                            class="absolute inset-0 bg-black/60 group-hover:bg-black/40 transition duration-300 z-10">
                                        </div>
                                    @else
                                        <div class="absolute inset-0 bg-gray-800 flex items-center justify-center z-0">
                                            <span class="text-gray-400">Aucune image</span>
                                        </div>
                                        <div class="absolute inset-0 bg-black/60 z-10"></div>
                                    @endif
                                    <div class="relative z-20 w-full flex flex-col items-center justify-between h-full">
                                        <h3
                                            class="text-lg font-semibold text-center tracking-wide mt-2 mb-1 text-white drop-shadow-lg group-hover:drop-shadow-2xl transition duration-300">
                                            {{ $result->display_name }}</h3>
                                        <form action="{{ route('shows.storeShow') }}" method="POST"
                                            class="w-full flex justify-center">
                                            @csrf
                                            <input type="hidden" name="show_id" value="{{ $result->id }}">
                                            <input type="submit" name="save_show" value="Ajouter à ma liste"
                                                class="!bg-blue-600 text-white rounded-lg px-4 py-2 font-semibold cursor-pointer mt-2 shadow !hover:bg-blue-700 !focus:bg-blue-800 !focus:shadow-lg !transition group-hover:scale-105 group-hover:ring-2 group-hover:ring-blue-400 group-hover:ring-offset-2" />
                                        </form>
                                    </div>
                                </article>
                            @endif
                        @endforeach
                    </div>
                </section>

            </x-bladewind::tab.content>
        </x-bladewind::tab.body>

    </x-bladewind::tab>

@endsection
