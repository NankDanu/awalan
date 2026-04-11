@if ($paginator->hasPages())
    <nav class="kt-datatable-pagination" role="navigation" aria-label="Pagination Navigation">
        @if ($paginator->onFirstPage())
            <span class="kt-datatable-pagination-button kt-datatable-pagination-prev" aria-disabled="true" aria-label="Previous page">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </span>
        @else
            <a class="kt-datatable-pagination-button kt-datatable-pagination-prev" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="kt-datatable-pagination-button kt-datatable-pagination-more" aria-disabled="true">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="kt-datatable-pagination-button active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="kt-datatable-pagination-button" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="kt-datatable-pagination-button kt-datatable-pagination-next" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        @else
            <span class="kt-datatable-pagination-button kt-datatable-pagination-next" aria-disabled="true" aria-label="Next page">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </span>
        @endif
    </nav>
@endif
