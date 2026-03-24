@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Recherche</h1>
    <form method="GET" class="mb-6">
        <input type="text" name="q" value="{{ $q }}" class="w-full rounded border px-3 py-2" placeholder="Mot-clé..." />
    </form>

    @if($articles)
        <div class="space-y-3">
            @foreach($articles as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="block rounded bg-white p-4 shadow">{{ $article->title }}</a>
            @endforeach
        </div>
        <div class="mt-6">{{ $articles->links() }}</div>
    @endif
@endsection
