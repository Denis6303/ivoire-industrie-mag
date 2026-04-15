@push('styles')
    <style>
        @media (max-width: 575.98px) {
            #search .search-popup {
                display: flex !important;
                align-items: center !important;
                gap: 8px !important;
                width: 100% !important;
            }
            #search .search-popup input[type="search"] {
                min-width: 0 !important;
                flex: 1 1 auto !important;
            }
            #search .search-popup .btn {
                flex: 0 0 auto !important;
                min-width: 52px !important;
                white-space: nowrap !important;
                padding-left: 12px !important;
                padding-right: 12px !important;
                font-size: 14px !important;
            }
        }
    </style>
@endpush

<div id="search">
    <button type="button" class="close" aria-label="{{ __('app.close') }}"><i class="fa-solid fa-xmark"></i></button>
    <form action="{{ route('search') }}" method="get">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-10 col-sm-8">
                    <div class="search-popup">
                        <input
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            autocomplete="off"
                        >
                        <button type="submit" class="btn btn-primary" aria-label="{{ __('header.search_button') }}">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                            <span class="visually-hidden">{{ __('header.search_button') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
