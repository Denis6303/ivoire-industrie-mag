@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Recherche</h1>
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-10">
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Mot-clé..." />
        </div>
        <div class="col-md-2">
            <button class="btn btn-ivm w-100">Rechercher</button>
        </div>
    </form>

    @if($articles)
        <div class="d-grid gap-3">
            @foreach($articles as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="card card-body card-mag text-decoration-none text-dark">{{ $article->title }}</a>
            @endforeach
        </div>
        <div class="mt-4">{{ $articles->links() }}</div>
    @endif
@endsection
