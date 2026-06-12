@if ($paginator->count())
    <div class="px-lg py-md border-t border-outline-variant bg-surface-container-low/30 flex justify-between items-center">
        <span class="text-xs text-on-surface-variant">Affichage de {{ $paginator->firstItem() }} à {{ $paginator->lastItem() }}
            sur {{ $paginator->total() }} {{ $label }}</span>
        <div class="flex gap-sm">
            @if ($paginator->onFirstPage())
                <button class="p-xs rounded-lg hover:bg-surface-variant text-outline opacity-30" disabled>
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="p-xs rounded-lg hover:bg-surface-variant text-outline flex items-center">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
            @endif

            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                <a href="{{ $url }}"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-medium
                {{ $page == $paginator->currentPage()
                    ? 'bg-primary text-on-primary font-bold'
                    : 'hover:bg-surface-variant text-primary' }}">
                    {{ $page }}
                </a>
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="p-xs rounded-lg hover:bg-surface-variant text-outline flex items-center">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            @else
                <button class="p-xs rounded-lg hover:bg-surface-variant text-outline opacity-30" disabled>
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            @endif
        </div>
    </div>
@endif
