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
                    class="grid gap-8 justify-items-center items-start grid-cols-5 xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 md:gap-6 sm:gap-4">

                    @foreach ($movies_datas->results as $movie)
                        @include('components.movie-card', ['movie' => $movie])
                    @endforeach
                </section>

            </x-bladewind::tab.content>


            <x-bladewind::tab.content name="inactive-cyan">
                <section
                    class="grid gap-8 justify-items-center items-start grid-cols-5 xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 md:gap-6 sm:gap-4">

                    @foreach ($popular_show->results as $show)
                        @include('shows.popular-card', ['show' => $show])
                    @endforeach
                </section>
            </x-bladewind::tab.content>
        </x-bladewind::tab.body>

    </x-bladewind::tab>



@endsection
