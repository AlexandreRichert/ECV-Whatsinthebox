<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>@yield('title')</title>
</head>

<body>
    <nav class="navbar">
        <ul class="navbar-left">
            <li><a href="{{ route('home') }}">Accueil</a></li>
        </ul>
        <ul class="navbar-right">
            <li><a href="{{ route('movies.popular') }}">Populaires</a></li>
            <li><a href="{{ route('movies.top') }}">Top</a></li>
        </ul>
        <form action="{{ route('movies.search') }}" method="GET">
            <input type="text" name="search" placeholder="Rechercher un film" value="{{ request('search') }}">
            <input type="hidden" value="" aria-label="Rechercher">
        </form>
        <button class="navbar-burger" id="navbarBurger" aria-label="Menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="navbar-dropdown" id="navbarDropdown">
            <a href="{{ route('movies.popular') }}">Populaires</a>
            <a href="{{ route('movies.top') }}">Top</a>
            <form action="{{ route('movies.search') }}" method="GET">
                <input type="text" name="search" placeholder="Rechercher un film" value="{{ request('search') }}">
                <input type="hidden" value="" aria-label="Rechercher">
            </form>
        </div>
    </nav>
    @yield('content')
    <script>
        const burger = document.getElementById('navbarBurger');
        const dropdown = document.getElementById('navbarDropdown');
        burger.addEventListener('click', function() {
            dropdown.classList.toggle('open');
            burger.setAttribute('aria-expanded', dropdown.classList.contains('open'));
        });
        window.addEventListener('resize', function() {
            if (window.innerWidth > 700) {
                dropdown.classList.remove('open');
                burger.setAttribute('aria-expanded', false);
            }
        });
    </script>
</body>

</html>
