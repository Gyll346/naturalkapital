@if ($paginator->hasPages())
    <div class="pagination clearfix">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-prev disabled">&laquo; Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-prev" rel="prev">&laquo; Sebelumnya</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="disabled">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="current">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-next" rel="next">Selanjutnya &raquo;</a>
        @else
            <span class="pagination-next disabled">Selanjutnya &raquo;</span>
        @endif
    </div>
@endif
