@extends('layouts.app')

@section('content')
    <h1 class="mb-4">{{ $category->name }}</h1>
    <div class="row g-3">
        @foreach($articles as $article)
            <div class="col-md-6 col-lg-4">
                <article class="card card-mag h-100">
                    <div class="card-body">
                        <h2 class="h6">{{ $article->title }}</h2>
                        <p class="small text-secondary">{{ $article->excerpt }}</p>
                        <a class="btn btn-outline-dark btn-sm" href="{{ route('articles.show', $article->slug) }}">Lire</a>
                    </div>
                </article>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $articles->links() }}</div>
@endsection
