@push('styles')
    <style>
        /* Onglets « Articles à lire » : soulignement actif (Nezzy), priorité sur style.css + Bootstrap */
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav.nav-tabs {
            flex-wrap: wrap;
            gap: 0 2px;
            row-gap: 6px;
            border-bottom: 1px solid #e4eaf7;
            margin-bottom: 0;
            padding: 0 0 0 2px;
            position: static;
            top: auto;
        }
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav-tabs .nav-item {
            margin-right: 0 !important;
        }
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav-tabs .nav-link {
            border: none !important;
            border-radius: 0 !important;
            padding: 10px 12px 14px !important;
            margin: 0;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #5c667d !important;
            background: transparent !important;
            border-bottom: 2px solid transparent !important;
            line-height: 1.2;
            transition: color 0.2s ease, border-color 0.2s ease;
        }
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav-tabs .nav-link:hover {
            color: #ff7800 !important;
            border-bottom-color: rgba(255, 120, 0, 0.4) !important;
        }
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav-tabs .nav-link.active,
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav-tabs .nav-link.active:focus,
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav-tabs .nav-link.active:focus-visible {
            color: #ff7800 !important;
            background: transparent !important;
            border-bottom: 2px solid #ff7800 !important;
            box-shadow: none !important;
        }
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav-tabs .nav-link:focus-visible {
            outline: 2px solid rgba(255, 120, 0, 0.45);
            outline-offset: 2px;
        }

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
        .sidebar-home-posts .blog-post.post-style-07 .sidebar-post-category:hover {
            color: #041f42;
        }
    </style>
@endpush

@php
    $fallbackSquare = asset('images/ivm-placeholder-square.svg');
@endphp

<div class="widget post-widget">
    <h6 class="widget-title">{{ __('sidebar.related_posts') }}</h6>
    <div class="news-tab sidebar-home-news-tab">
        <ul class="nav nav-tabs border-0" id="homeSidebarPostsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab-latest" data-bs-toggle="tab" data-bs-target="#home-pane-latest" type="button" role="tab" aria-controls="home-pane-latest" aria-selected="true">{{ __('sidebar.tab_latest') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="home-tab-trending" data-bs-toggle="tab" data-bs-target="#home-pane-trending" type="button" role="tab" aria-controls="home-pane-trending" aria-selected="false">{{ __('sidebar.tab_trending') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="home-tab-popular" data-bs-toggle="tab" data-bs-target="#home-pane-popular" type="button" role="tab" aria-controls="home-pane-popular" aria-selected="false">{{ __('sidebar.tab_popular') }}</button>
            </li>
        </ul>
        <div class="tab-content pt-4 sidebar-home-posts" id="homeSidebarPostsTabContent">
            @foreach (['latest' => $sidebarLatest, 'trending' => $sidebarTrending, 'popular' => $sidebarPopular] as $key => $posts)
                @php
                    $paneId = 'home-pane-'.$key;
                    $isFirst = $key === 'latest';
                @endphp
                <div class="tab-pane fade @if ($isFirst) show active @endif" id="{{ $paneId }}" role="tabpanel" aria-labelledby="home-tab-{{ $key }}" tabindex="0">
                    @forelse ($posts as $article)
                        @php $cover = article_cover($article->cover_image); @endphp
                        <div class="blog-post post-style-07">
                            <div class="post-image">
                                @if ($cover)
                                    <a href="{{ route('articles.show', $article->slug) }}">
                                        <span class="d-block ratio ratio-1x1 overflow-hidden rounded">
                                            <img class="w-100 h-100" style="object-fit: cover;" src="{{ $cover }}" alt="{{ $article->cover_alt ?? $article->title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $fallbackSquare }}';">
                                        </span>
                                    </a>
                                @else
                                    <a href="{{ route('articles.show', $article->slug) }}" class="d-block bg-light ratio ratio-1x1 rounded"></a>
                                @endif
                            </div>
                            <div class="blog-post-details">
                                @if ($article->category)
                                    <a
                                        class="sidebar-post-category"
                                        href="{{ route('categories.show', $article->category->slug) }}"
                                        style="background: {{ $article->category->color ?: '#0d6efd' }}; color: #fff; padding: 2px 10px; border-radius: 999px;"
                                    >{{ $article->category->name }}</a>
                                @endif
                                <h6 class="blog-title">
                                    <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                </h6>
                                @if ($article->published_at)
                                    <div class="blog-post-meta">
                                        <div class="blog-post-time">
                                            <a href="{{ route('articles.show', $article->slug) }}"><i class="fa-solid fa-calendar-days"></i>{{ $article->published_at->translatedFormat('j M Y') }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">{{ __('sidebar.no_posts_tab') }}</p>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>
</div>
