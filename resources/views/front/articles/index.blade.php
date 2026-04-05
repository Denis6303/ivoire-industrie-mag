@extends('layouts.front')

@section('title', 'Articles')

@section('content')
    @include('front.partials.page-header', ['title' => 'Articles'])

    <section class="space-ptb">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    @forelse ($articles as $article)
                        <x-article-card :article="$article" style="11" />
                    @empty
                        <p class="text-muted">Aucun article publié pour le moment.</p>
                    @endforelse
                    <div class="mt-4">
                        {{ $articles->links() }}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar mt-lg-0">
                        <div class="widget sidebar-category">
                            <h6 class="widget-title">Catégories</h6>
                            <ul>
                                @foreach ($navCategories as $cat)
                                    <li>
                                        <a href="{{ route('categories.show', $cat->slug) }}">
                                            <div class="category">
                                                <div class="category-name">
                                                    <h6>{{ $cat->name }}</h6>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
