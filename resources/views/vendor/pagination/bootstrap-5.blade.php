@if ($paginator->hasPages())
    <style>
        .pagination .page-link {
            color: #ff7800;
        }
        .pagination .page-link:hover {
            color: #ff7800;
            border-color: rgba(255, 120, 0, 0.35);
            background: rgba(255, 120, 0, 0.08);
        }
        .pagination .page-item.active .page-link {
            background-color: #ff7800;
            border-color: #ff7800;
            color: #fff;
        }
        .pagination .page-item.disabled .page-link {
            color: rgba(255, 120, 0, 0.5);
        }
        .pagination .page-link:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 120, 0, 0.25);
        }
    </style>
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="d-flex justify-content-end">
        <ul class="pagination mb-0">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">&lsaquo;</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
