@extends('layouts.front')

@section('title', __('errors.not_found_title'))

@section('content')
    @php
        $supported = config('ivoireindustriemag.supported_locales', ['fr', 'en']);
        $seg = request()->segment(1);
        $locale = (is_string($seg) && in_array($seg, $supported, true)) ? $seg : config('app.locale', 'fr');
    @endphp
    <section class="space-ptb py-5">
        <div class="container text-center">
            <h1 class="display-4 mb-3">404</h1>
            <p class="lead mb-4">{{ __('errors.not_found_lead') }}</p>
            <a href="{{ route('home', ['locale' => $locale]) }}" class="btn btn-primary">{{ __('nav.home') }}</a>
      </div>
    </section>
@endsection
