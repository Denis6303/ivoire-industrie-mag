@extends('layouts.app')

@section('content')
    <h1 class="mb-4">{{ $sector->name }}</h1>
    <div class="row g-3">
        @foreach($articles as $article)
            <div class="col-md-6 col-lg-4">
                <a class="card card-body card-mag text-decoration-none text-dark h-100" href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $articles->links() }}</div>
@endsection
