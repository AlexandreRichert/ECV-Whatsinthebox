<div>
    <select id="{{ $id }}" name="{{ $id }}"
        class="pl-4 pr-8 py-2 rounded-lg border-2 border-blue-600 bg-white text-blue-900 font-semibold shadow focus:ring-2 focus:ring-blue-400 transition-all hover:border-blue-800"
        @if ($onChange) onchange="{{ $onChange }}" @endif>
        @foreach ($options as $value => $label)
            <option value="{{ $value }}" @if ($value == $selected) selected @endif>{{ $label }}
            </option>
        @endforeach
    </select>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectElement = document.getElementById('{{ $id }}');
        if (selectElement) {
            selectElement.addEventListener('change', function() {
                const selectedValue = this.value;
                console.log('Selected value:', selectedValue);
            });
        }
    });
</script>
