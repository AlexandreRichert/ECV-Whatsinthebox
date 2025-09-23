<form action="{{ route('movies.search') }}" method="GET" class="flex items-center gap-2 w-full">
    <input type="text" name="search" placeholder="Rechercher un film" value="{{ request('search') }}"
        class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" />
    <input type="hidden" aria-label="Rechercher un film"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition ml-2" value="" />
</form>
