<script>
    document.addEventListener('DOMContentLoaded', function() {
        const burger = document.getElementById('navbarBurger');
        const dropdown = document.getElementById('navbarDropdown');
        if (burger && dropdown) {
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
        }
    });
</script>
<nav class="navbar">
    <nav
        class="flex h-[70px] justify-between items-center px-12 bg-[rgba(24,24,40,0.7)] shadow-lg rounded-b-2xl backdrop-blur-md sticky top-0 z-10">
        <ul class="flex gap-4 mr-auto">
            <li><a href="{{ route('home') }}"
                    class="text-white font-bold px-4 py-2 rounded-lg transition hover:bg-white hover:text-blue-900">Accueil</a>
            </li>
        </ul>
        <ul class="flex gap-4 ml-auto hidden md:flex">
            <li><a href="{{ route('movies.popular') }}"
                    class="text-white font-bold px-4 py-2 rounded-lg transition hover:bg-white hover:text-blue-900">Populaires</a>
            </li>
            <li><a href="{{ route('movies.top') }}"
                    class="text-white font-bold px-4 py-2 rounded-lg transition hover:bg-white hover:text-blue-900">Top</a>
            </li>
        </ul>
        <form action="{{ route('movies.search') }}" method="GET" class="hidden md:flex items-center gap-3">
            @include('components.search-bar')
        </form>
        <button
            class="md:hidden flex items-center justify-center bg-transparent border-none cursor-pointer w-10 h-10 ml-3"
            id="navbarBurger" aria-label="Menu" aria-expanded="false">
            <span class="block w-7 h-1 my-1 bg-white rounded transition-all"></span>
            <span class="block w-7 h-1 my-1 bg-white rounded transition-all"></span>
            <span class="block w-7 h-1 my-1 bg-white rounded transition-all"></span>
        </button>
        <div class="navbar-dropdown hidden flex-col absolute top-[60px] right-4 bg-[rgba(24,24,40,0.98)] rounded-xl shadow-2xl p-6 z-50 gap-4"
            id="navbarDropdown">
            <a href="{{ route('movies.popular') }}"
                class="text-white font-bold px-4 py-2 rounded-lg transition hover:bg-white hover:text-blue-900">Populaires</a>
            <a href="{{ route('movies.top') }}"
                class="text-white font-bold px-4 py-2 rounded-lg transition hover:bg-white hover:text-blue-900">Top</a>
            <form action="{{ route('movies.search') }}" method="GET" class="flex items-center gap-3 mt-2">
                @include('components.search-bar')
            </form>
        </div>
    </nav>
