@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Articles</h1>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach($articles as $article)
            <article class="rounded bg-white p-4 shadow">
                <h2 class="font-semibold">{{ $article->title }}</h2>
                <p class="mt-2 text-sm text-gray-600">{{ $article->excerpt }}</p>
                <a class="mt-2 inline-block text-sm text-emerald-700" href="{{ route('articles.show', $article->slug) }}">Lire</a>
            </article>
        @endforeach
    </div>
    <div class="mt-6">{{ $articles->links() }}</div>
@endsection
