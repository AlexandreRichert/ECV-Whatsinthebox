@extends('base')

@section('title', 'Films Populaire')

@section('content')

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
    <x-bladewind::tab name="indigo-tab" color="cyan">
        <x-slot name="headings">

            <x-bladewind::tab.heading name="cyan" active="true" label="Films" />

            <x-bladewind::tab.heading name="inactive-cyan" label="Séries" />

        </x-slot>

        <x-bladewind::tab.body>

            <x-bladewind::tab.content name="cyan" active="true">
                <section
                    class="grid gap-6 sm:gap-4 justify-items-center items-start grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">

                    @foreach ($movies_datas->results as $movie)
                        <x-movie-card :movie="$movie" :showSeenCheckbox="false">
                            <form action="{{ route('movies.store') }}" method="POST" class="w-full flex justify-center">
                                @csrf
                                <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                <input type="submit" name="save_movie" value="Ajouter à ma liste"
                                    class="!bg-blue-600 text-white rounded-lg px-4 py-2 font-semibold cursor-pointer mt-2 shadow hover:bg-blue-700 focus:bg-blue-800 focus:shadow-lg transition group-hover:scale-105 group-hover:ring-2 group-hover:ring-blue-400 group-hover:ring-offset-2" />
                            </form>
                        </x-movie-card>
                    @endforeach
                </section>

            </x-bladewind::tab.content>


            <x-bladewind::tab.content name="inactive-cyan">
                <section
                    class="grid gap-6 sm:gap-4 justify-items-center items-start grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">

                    @foreach ($popular_show->results as $show)
                        @include('shows.popular-card', ['show' => $show])
                    @endforeach
                </section>
            </x-bladewind::tab.content>
        </x-bladewind::tab.body>

    </x-bladewind::tab>



@endsection
