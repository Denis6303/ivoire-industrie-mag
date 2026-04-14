<div id="search">
    <button type="button" class="close" aria-label="{{ __('app.close') }}"><i class="fa-solid fa-xmark"></i></button>
    <form action="{{ route('search') }}" method="get">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-9 col-sm-8">
                    <div class="search-popup">
                        <input
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            autocomplete="off"
                        >
                        <button type="submit" class="btn btn-primary">{{ __('header.search_button') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
