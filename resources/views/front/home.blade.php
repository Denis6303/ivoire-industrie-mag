@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Actualité de l'industrie ivoirienne</h1>

    @if($featured)
        <article class="card card-mag mb-4">
            <div class="card-body">
                <span class="badge text-bg-warning mb-2">A la une</span>
                <h2 class="h4">{{ $featured->title }}</h2>
                <p class="text-secondary">{{ $featured->excerpt }}</p>
                <a class="btn btn-ivm btn-sm" href="{{ route('articles.show', $featured->slug) }}">Lire l'article</a>
            </div>
        </article>
    @endif

    <div class="row g-3">
        @foreach($latest as $article)
            <div class="col-md-6 col-lg-4">
                <article class="card card-mag h-100">
                    <div class="card-body">
                        <h3 class="h6">{{ $article->title }}</h3>
                        <p class="small text-secondary">{{ $article->excerpt }}</p>
                        <a class="btn btn-outline-dark btn-sm" href="{{ route('articles.show', $article->slug) }}">Voir</a>
                    </div>
                </article>
            </div>
        @endforeach
    </div>
@endsection
