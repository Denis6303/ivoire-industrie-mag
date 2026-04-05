@extends('layouts.front')

@section('title', __('search.title'))

@section('content')
    @include('front.partials.page-header', ['title' => __('search.title')])

    <section class="space-ptb">
        <div class="container">
            <form action="{{ route('search') }}" method="get" class="row g-2 mb-5">
                <div class="col-md-8">
                    <input type="search" name="q" value="{{ $q }}" class="form-control form-control-lg" placeholder="{{ __('search.placeholder_page') }}" minlength="2">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">{{ __('search.submit') }}</button>
                </div>
            </form>

            @if ($q === '')
                <p class="text-muted">{{ __('search.hint_empty') }}</p>
            @elseif ($articles === null)
                <p class="text-muted">{{ __('search.hint_short') }}</p>
            @elseif ($articles->isEmpty())
                <p>{{ __('search.no_results', ['q' => $q]) }}</p>
            @else
                <p class="mb-4">{{ __('search.results_count', ['count' => $articles->total(), 'q' => $q]) }}</p>
                @foreach ($articles as $article)
                    <x-article-card :article="$article" style="11" />
                @endforeach
                <div class="mt-4">{{ $articles->links() }}</div>
            @endif
        </div>
    </section>
@endsection
