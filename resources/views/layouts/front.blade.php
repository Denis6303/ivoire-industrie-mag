<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('front.partials.head')
    @stack('styles')
</head>
<body>
    @include('front.partials.loader')
    @include('front.partials.header')
    @include('front.partials.offcanvas')
    @include('front.partials.search-overlay')

    @if (session('success'))
        <div class="container pt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        </div>
    @endif

    @yield('content')

    @include('front.partials.footer')
    @include('front.partials.back-to-top')
    @include('front.partials.scripts')
    @stack('scripts')
</body>
</html>
