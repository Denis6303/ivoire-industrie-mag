@extends('layouts.front')

@section('title', e($article->meta_title ?? $article->title))
@section('meta_description', e($article->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($article->excerpt ?? $article->content), 160)))
@push('styles')
    <style>
        .sidebar-home-posts .blog-post.post-style-07 {
            padding: 12px;
            margin-bottom: 12px;
            align-items: center;
            justify-content: flex-start;
            column-gap: 0;
        }
        .sidebar-home-posts .blog-post.post-style-07 .post-image {
            flex: 0 0 88px;
            margin-right: 18px;
        }
        .sidebar-home-posts .blog-post.post-style-07 .blog-post-details {
            width: auto;
            flex: 1;
            min-width: 0;
            padding-left: 0;
        }
        .sidebar-home-posts .blog-post.post-style-07 .blog-post-details .blog-title {
            font-size: 16px;
            line-height: 1.3;
            padding-top: 0;
            margin-bottom: 0;
        }
        .sidebar-home-posts .blog-post.post-style-07 .blog-post-details .blog-post-meta {
            padding-top: 8px;
        }
        .sidebar-home-posts .blog-post.post-style-07 .sidebar-post-category {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #ff7800;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 4px;
        }

        .ivm-article-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        .ivm-tag-label {
            display: inline-block;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #fff;
            background: #1f2b4d;
            border: 1px solid #1f2b4d;
            padding: 5px 10px;
            border-radius: 2px;
            line-height: 1.2;
        }
        .ivm-article-tag {
            display: inline-block;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: #1f2b4d;
            border: 1px solid #1f2b4d;
            padding: 5px 10px;
            border-radius: 2px;
            text-decoration: none;
            line-height: 1.2;
            background: #fff;
        }
        .ivm-article-tag:hover {
            color: #fff;
            background: #1f2b4d;
        }
    </style>
@endpush

@section('content')
    @include('front.partials.page-header', [
        'title' => \Illuminate\Support\Str::limit($article->title, 80),
        'breadcrumbItems' => $article->category
            ? [['label' => $article->category->name, 'url' => route('categories.show', ['slug' => $article->category->slug])]]
            : [],
    ])

    <section class="space-ptb">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 blog-single">
                    <article class="blog-post-info">
                        <div class="blog-content pb-0">
                            <div class="blog-post-title mb-3">
                                <h1 class="mb-0 h3">{{ $article->title }}</h1>
                            </div>

                            @php $cover = article_cover($article->cover_image); @endphp
                            @if ($cover)
                                <div class="blog-post-image mb-4">
                                    <div class="overflow-hidden rounded" style="height: 360px;">
                                        <img class="w-100 h-100" style="object-fit: cover;" src="{{ $cover }}" alt="{{ $article->cover_alt ?? $article->title }}" loading="eager" onerror="this.onerror=null;this.src='{{ asset('images/ivm-placeholder-16x9.svg') }}';">
                                    </div>
                                </div>
                            @endif
                            <div class="blog-post post-style-07 border-0 py-4 px-0">
                            <div class="blog-post-details">
                                    <div class="blog-post-meta p-0 flex-wrap">
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
                            @if ($article->excerpt)
                                <div class="mb-4">
                                    <p class="lead fw-bold mb-0">{{ $article->excerpt }}</p>
                                </div>
                            @endif
                            <div class="article-body">
                                {!! article_body_html($article->content) !!}
                            </div>
                            @if ($article->author)
                                <div class="blog-post-user mt-4 mb-2">
                                    <span>par <span style="color:#243e5d;">{{ $article->signature ?: $article->author->name }}</span></span>
                                </div>
                            @endif
                            @if ($article->tags->isNotEmpty())
                                <div class="ivm-article-tags mt-4">
                                    <span class="ivm-tag-label">Tags</span>
                                    @foreach ($article->tags as $tag)
                                        <a class="ivm-article-tag" href="{{ route('search', ['q' => $tag->name]) }}">{{ $tag->name }}</a>
                                    @endforeach
                          </div>
                            @endif
                            <hr class="my-4">
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
                        <div class="widget post-widget">
                            <h6 class="widget-title">Les plus récents</h6>
                            <div class="sidebar-home-posts pt-2">
                                @forelse ($recentArticles as $recent)
                                    @php $recentCover = article_cover($recent->cover_image); @endphp
                                    <div class="blog-post post-style-07">
                                        <div class="post-image">
                                            @if ($recentCover)
                                                <a href="{{ route('articles.show', ['slug' => $recent->slug]) }}">
                                                    <span class="d-block ratio ratio-1x1 overflow-hidden rounded">
                                                        <img class="w-100 h-100" style="object-fit: cover;" src="{{ $recentCover }}" alt="{{ $recent->cover_alt ?? $recent->title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/ivm-placeholder-square.svg') }}';">
                                                    </span>
                                                </a>
                                            @else
                                                <a href="{{ route('articles.show', ['slug' => $recent->slug]) }}" class="d-block bg-light ratio ratio-1x1 rounded"></a>
                                            @endif
                                        </div>
                                        <div class="blog-post-details">
                                            @if ($recent->category)
                                                <a
                                                    class="sidebar-post-category"
                                                    href="{{ route('categories.show', ['slug' => $recent->category->slug]) }}"
                                                    style="background: {{ $recent->category->color ?: '#0d6efd' }}; color: #fff; padding: 2px 10px; border-radius: 999px;"
                                                >{{ $recent->category->name }}</a>
                                            @endif
                                            <h6 class="blog-title">
                                                <a href="{{ route('articles.show', ['slug' => $recent->slug]) }}">{{ $recent->title }}</a>
                                            </h6>
                                            @if ($recent->published_at)
                                                <div class="blog-post-meta">
                                                    <div class="blog-post-time">
                                                        <a href="{{ route('articles.show', ['slug' => $recent->slug]) }}"><i class="fa-solid fa-calendar-days"></i>{{ $recent->published_at->translatedFormat('j M Y') }}</a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small mb-0">Aucun article récent.</p>
                                @endforelse
                            </div>
                        </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
