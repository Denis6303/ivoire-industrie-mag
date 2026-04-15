<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('front.partials.head')
    <style>
        .ivm-ad {
            width: 100%;
            overflow: hidden;
        }
        .ivm-ad a {
            display: block;
            width: 100%;
        }
        .ivm-ad img {
            width: 100%;
            display: block;
            border-radius: 0.4rem;
        }
        .ivm-ad-horizontal img {
            max-height: 180px;
            object-fit: cover;
        }
        .ivm-ad-vertical img {
            max-height: 540px;
            object-fit: cover;
        }
        @media (max-width: 767.98px) {
            .ivm-ad-horizontal img {
                max-height: 120px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('front.partials.loader')
    @include('front.partials.header')
    @include('front.partials.offcanvas')
    @include('front.partials.search-overlay')
    <div class="container"><hr class="my-0"></div>
    @if (!empty($adHeader))
        <div class="container py-2">
            @include('front.partials.ad-banner', ['ad' => $adHeader, 'variant' => 'horizontal'])
        </div>
    @endif

    @if (session('success'))
        <div class="container pt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('app.close') }}"></button>
            </div>
        </div>
    @endif

    @yield('content')

    @if (!empty($adFooter))
        <div class="container py-3">
            @include('front.partials.ad-banner', ['ad' => $adFooter, 'variant' => 'horizontal'])
        </div>
    @endif

    @include('front.partials.footer')
    @include('front.partials.back-to-top')
    @include('front.partials.scripts')
    @stack('scripts')
</body>
</html>
