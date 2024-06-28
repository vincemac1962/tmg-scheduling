@if ($paginator->hasPages())
    <div class="flex items-center justify-between">
        @if (!$paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-blue-500 hover:text-blue-700">Previous</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-4 py-2">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-4 py-2">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 text-blue-500 hover:text-blue-700">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-blue-500 hover:text-blue-700">Next</a>
        @else
            <span class="px-4 py-2">Next</span>
        @endif
    </div>
@endif
