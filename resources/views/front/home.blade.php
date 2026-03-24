@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-3xl font-bold text-emerald-900">Actualité de l'industrie ivoirienne</h1>

    @if($featured)
        <article class="mb-8 rounded-lg bg-white p-6 shadow">
            <p class="mb-2 text-xs uppercase tracking-wide text-amber-600">A la une</p>
            <h2 class="text-2xl font-semibold">{{ $featured->title }}</h2>
            <p class="mt-2 text-gray-600">{{ $featured->excerpt }}</p>
            <a class="mt-4 inline-block text-emerald-700" href="{{ route('articles.show', $featured->slug) }}">Lire l'article</a>
        </article>
    @endif

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($latest as $article)
            <article class="rounded-lg bg-white p-4 shadow">
                <h3 class="font-semibold">{{ $article->title }}</h3>
                <p class="mt-2 text-sm text-gray-600">{{ $article->excerpt }}</p>
                <a class="mt-3 inline-block text-sm text-emerald-700" href="{{ route('articles.show', $article->slug) }}">Voir</a>
            </article>
        @endforeach
    </div>
@endsection
