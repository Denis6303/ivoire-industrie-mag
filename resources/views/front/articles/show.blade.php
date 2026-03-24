@extends('layouts.app')

@section('content')
    <article class="mx-auto max-w-3xl rounded bg-white p-8 shadow">
        <h1 class="text-3xl font-bold">{{ $article->title }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ optional($article->published_at)->format('d/m/Y') }} - {{ $article->view_count }} vues</p>
        <div class="prose mt-6 max-w-none">{!! $article->content !!}</div>
    </article>

    <section class="mt-10">
        <h2 class="mb-4 text-xl font-semibold">Articles similaires</h2>
        <div class="grid gap-4 md:grid-cols-3">
            @foreach($related as $item)
                <a href="{{ route('articles.show', $item->slug) }}" class="rounded bg-white p-4 shadow">{{ $item->title }}</a>
            @endforeach
        </div>
    </section>
@endsection
