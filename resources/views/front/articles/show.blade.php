@extends('layouts.front')

@section('title', $article->meta_title ?? $article->title)
@section('meta_description', e($article->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($article->excerpt ?? $article->content), 160)))

@section('content')
    @include('front.partials.page-header', [
        'title' => \Illuminate\Support\Str::limit($article->title, 80),
        'breadcrumbItems' => $article->category
            ? [['label' => $article->category->name, 'url' => route('categories.show', $article->category->slug)]]
            : [],
    ])

    <section class="space-ptb">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 blog-single">
                    <article class="blog-post-info">
                        <div class="blog-content pb-0">
                            @php $cover = article_cover($article->cover_image); @endphp
                            @if ($cover)
                                <div class="blog-post-image mb-4">
                                    <img class="img-fluid" src="{{ $cover }}" alt="{{ $article->cover_alt ?? $article->title }}">
                                </div>
                            @endif
                            <div class="blog-post-title">
                                <h1 class="mb-0 h3">{{ $article->title }}</h1>
                            </div>
                            <div class="blog-post post-style-07 border-0 py-4 px-0">
                                <div class="blog-post-details">
                                    <div class="blog-post-meta p-0 flex-wrap">
                                        @if ($article->author)
                                            <div class="blog-post-user">
                                                <span>par {{ $article->author->name }}</span>
                                            </div>
                                        @endif
                                        @if ($article->published_at)
                                            <div class="blog-post-time">
                                                <span><i class="fa-solid fa-calendar-days"></i>{{ $article->published_at->translatedFormat('j F Y') }}</span>
                                            </div>
                                        @endif
                                        @if ($article->reading_time)
                                            <div class="blog-post-time">
                                                <span><i class="fa-regular fa-clock"></i>{{ $article->reading_time }} min</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="article-body">
                                {!! $article->content !!}
                            </div>
                            @if ($article->tags->isNotEmpty())
                                <div class="badges mt-4">
                                    @foreach ($article->tags as $tag)
                                        <span class="btn btn-sm btn-outline-primary me-1 mb-1">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </article>

                    <div class="bg-white mb-4 mt-5">
                        <h6 class="widget-title text-uppercase fw-bolder">Laisser un commentaire</h6>
                        <div class="blog-sidebar-post-divider mb-4"></div>
                        <form class="row" method="POST" action="{{ route('comments.store', $article) }}">
                            @csrf
                            @guest
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="guest_name" class="form-control @error('guest_name') is-invalid @enderror" placeholder="Nom" value="{{ old('guest_name') }}">
                                    @error('guest_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" name="guest_email" class="form-control @error('guest_email') is-invalid @enderror" placeholder="E-mail" value="{{ old('guest_email') }}">
                                    @error('guest_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            @endguest
                            <div class="col-12 mb-3">
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="4" placeholder="Votre commentaire" required>{{ old('content') }}</textarea>
                                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Publier</button>
                            </div>
                        </form>
                    </div>

                    @if ($article->comments->isNotEmpty())
                        <div class="mt-4">
                            <h6 class="widget-title">Commentaires</h6>
                            @foreach ($article->comments as $comment)
                                <div class="border-bottom py-3">
                                    <strong>{{ $comment->user?->name ?? $comment->guest_name ?? 'Visiteur' }}</strong>
                                    <small class="text-muted ms-2">{{ $comment->created_at?->translatedFormat('j M Y à H:i') }}</small>
                                    <p class="mb-0 mt-2">{{ $comment->content }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($related->isNotEmpty())
                        <div class="bg-white mt-5">
                            <div class="section-title">
                                <h2 class="mb-0"><i class="fa-solid fa-bolt-lightning"></i> Sur le même sujet</h2>
                            </div>
                            <div class="row mt-3">
                                @foreach ($related as $rel)
                                    <div class="col-md-4 mb-3">
                                        <x-article-card :article="$rel" style="02" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
