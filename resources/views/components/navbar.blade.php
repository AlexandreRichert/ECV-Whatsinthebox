<script>
    document.addEventListener('DOMContentLoaded', function() {
        const burger = document.getElementById('navbarBurger');
        const dropdown = document.getElementById('navbarDropdown');
        if (burger && dropdown) {
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    dropdown.classList.add('hidden');
                }
                burger.addEventListener('click', function() {
                    if (dropdown.classList.contains('hidden')) {
                        dropdown.classList.remove('hidden');
                        dropdown.classList.add('flex !important');
                    } else {
                        dropdown.classList.add('hidden');
                        dropdown.classList.remove('flex !important');
                    }
                });

            });
        }
    });
</script>
<nav class="navbar">
    <nav
        class="flex h-[70px] justify-between items-center px-6 bg-[rgba(24,24,40,0.7)] shadow-lg rounded-b-2xl backdrop-blur-md sticky top-0 z-10">
        <!-- Accueil à gauche -->
        <ul class="flex gap-4 mr-auto">
            <li><a href="{{ route('home') }}"
                    class="text-white font-bold px-4 py-2 rounded-lg transition hover:bg-white hover:text-blue-900">Accueil</a>
            </li>
        </ul>
        <!-- Menu de droite (desktop) -->
        <div class="flex items-center gap-4 ml-auto" id="navbarRight">
            <a href="{{ route('movies.popular') }}"
                class="text-white font-bold px-4 py-2 rounded-lg transition hover:bg-white hover:text-blue-900">Populaires</a>
            <a href="{{ route('movies.top') }}"
                class="text-white font-bold px-4 py-2 rounded-lg transition hover:bg-white hover:text-blue-900">Top</a>
            <form action="{{ route('movies.search') }}" method="GET" class="flex items-center gap-3">
                @include('components.search-bar')
            </form>
        </div>
        <!-- Burger menu (mobile) -->
        <button class="flex items-center justify-center bg-transparent border-none cursor-pointer w-10 h-10 ml-3"
            id="navbarBurger" aria-label="Menu">
            <span class="block w-7 h-1 my-1 bg-white rounded transition-all"></span>
            <span class="block w-7 h-1 my-1 bg-white rounded transition-all"></span>
            <span class="block w-7 h-1 my-1 bg-white rounded transition-all"></span>
        </button>
        <div class="navbar-dropdown hidden flex-col absolute top-[70px] right-4 bg-[rgba(24,24,40,0.98)] rounded-xl shadow-2xl p-6 z-50 gap-4 w-56 md:hidden"
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const burger = document.getElementById('navbarBurger');
            const dropdown = document.getElementById('navbarDropdown');
            const navbarRight = document.getElementById('navbarRight');

            function handleResize() {
                if (window.innerWidth < 768) {
                    navbarRight.style.display = 'none';
                    burger.style.display = 'flex';
                } else {
                    navbarRight.style.display = 'flex';
                    burger.style.display = 'none';
                    dropdown.classList.add('hidden');
                }
            }
            handleResize();
            window.addEventListener('resize', handleResize);
            if (burger && dropdown) {
                burger.addEventListener('click', function() {
                    dropdown.classList.toggle('hidden');
                });
            }
        });
    </script>
