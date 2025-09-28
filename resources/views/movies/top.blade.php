@extends('base')

@section('title', 'Les mieux notés')

@section('content')
    <h2 class="text-4xl font-extrabold text-white text-center !mt-16 drop-shadow-lg">Les mieux notés</h2>
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
                <section class="section-top10 rounded-xl p-6 mb-8 shadow-lg backdrop-blur-md bg-[#193a5e]/60">
                    <h2 class="text-2xl font-bold text-white text-left !mb-10">Top 10</h2>
                    <div
                        class="grid gap-6 sm:gap-4 justify-items-center items-start grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                        @foreach (array_slice($movies_datas, 0, 10) as $movie)
                            @include('components.movie-card', [
                                'movie' => $movie,
                                'showRank' => true,
                                'rank' => $movie->rank,
                            ])
                        @endforeach
                    </div>
                </section>
                <section class="rounded-xl p-6 shadow-md backdrop-blur-md bg-[#193a5e]/60">
                    <div
                        class="grid gap-6 sm:gap-4 justify-items-center items-start grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                        @foreach (array_slice($movies_datas, 10) as $movie)
                            @include('components.movie-card', [
                                'movie' => $movie,
                                'showRank' => true,
                                'rank' => $movie->rank,
                            ])
                        @endforeach

                    </div>
                </section>

            </x-bladewind::tab.content>


            <x-bladewind::tab.content name="inactive-cyan">
                <section class="section-top10 rounded-xl p-6 mb-8 shadow-lg backdrop-blur-md bg-[#193a5e]/60">
                    <h2 class="text-2xl font-bold text-white text-center !mb-10">Top 10</h2>
                    <div
                        class="grid gap-6 sm:gap-4 justify-items-center items-start grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                        @foreach (array_slice($shows_datas, 0, 10) as $show)
                            @include('components.show-card', [
                                'show' => $show,
                                'showRank' => true,
                                'rank' => $show->rank,
                            ])
                        @endforeach
                    </div>
                </section>
                <section class="rounded-xl p-6 shadow-md backdrop-blur-md bg-[#193a5e]/60">
                    <div
                        class="grid gap-6 sm:gap-4 justify-items-center items-start grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                        @foreach (array_slice($shows_datas, 10) as $show)
                            @include('components.show-card', [
                                'show' => $show,
                                'showRank' => true,
                                'rank' => $show->rank,
                            ])
                        @endforeach

                    </div>
                </section>

            </x-bladewind::tab.content>
        </x-bladewind::tab.body>

    </x-bladewind::tab>

@endsection
