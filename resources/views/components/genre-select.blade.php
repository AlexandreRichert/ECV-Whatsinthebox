@props(['genres', 'selected'])
<form action="{{ route('home') }}" method="GET" class="flex items-center justify-center gap-3 mb-6">
    <select name="genre_id" onchange="this.form.submit()"
        class="px-4 py-2 rounded-lg border-2 border-blue-600 bg-white text-blue-900 font-semibold shadow focus:ring-2 focus:ring-blue-400 transition-all hover:border-blue-800">
        <option value="" class="font-bold">Tous les genres</option>
        @if (isset($genres) && count($genres))
            @foreach ($genres as $genre)
                <option value="{{ $genre->id }}" @if ($selected == $genre->id) selected @endif>
                    {{ $genre->name }} ({{ ($genre->movies_count ?? 0) + ($genre->shows_count ?? 0) }})
                </option>
            @endforeach
        @endif
    </select>
</form>
