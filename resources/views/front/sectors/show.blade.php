@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">{{ $sector->name }}</h1>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach($articles as $article)
            <a class="rounded bg-white p-4 shadow" href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
        @endforeach
    </div>
    <div class="mt-6">{{ $articles->links() }}</div>
@endsection
