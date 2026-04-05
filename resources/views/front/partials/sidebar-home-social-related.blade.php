@php
    $social = config('ivoireindustriemag.social', []);
@endphp

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
            color: #30c96a !important;
            border-bottom-color: rgba(48, 201, 106, 0.4) !important;
        }
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav-tabs .nav-link.active,
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav-tabs .nav-link.active:focus,
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav-tabs .nav-link.active:focus-visible {
            color: #30c96a !important;
            background: transparent !important;
            border-bottom: 2px solid #30c96a !important;
            box-shadow: none !important;
        }
        .sidebar .widget.post-widget .sidebar-home-news-tab .nav-tabs .nav-link:focus-visible {
            outline: 2px solid rgba(48, 201, 106, 0.45);
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
            color: #30c96a;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 4px;
        }
        .sidebar-home-posts .blog-post.post-style-07 .sidebar-post-category:hover {
            color: #041f42;
        }
    </style>
@endpush

<div class="widget mt-4">
    <h6 class="widget-title">{{ __('sidebar.follow_social') }}</h6>
    <div class="follow-style-01">
        <div class="row g-2">
            <div class="col-6 facebook-fans">
                <div class="social-box">
                    <div class="social">
                        <a href="{{ $social['facebook']['url'] ?? '#' }}" class="fans-icon" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <span>{{ $social['facebook']['count'] ?? '—' }}</span>
                    </div>
                    <div class="follower-btn fans"><a href="{{ $social['facebook']['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ __('sidebar.social_fans') }}</a></div>
                </div>
            </div>
            <div class="col-6 twitter-follower">
                <div class="social-box">
                    <div class="social">
                        <a href="{{ $social['twitter']['url'] ?? '#' }}" class="twitter-icon" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <span>{{ $social['twitter']['count'] ?? '—' }}</span>
                    </div>
                    <div class="follower-btn follower"><a href="{{ $social['twitter']['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ __('sidebar.social_followers') }}</a></div>
                </div>
            </div>
            <div class="col-6 you-tube">
                <div class="social-box">
                    <div class="social">
                        <a href="{{ $social['youtube']['url'] ?? '#' }}" class="tube-icon" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                        <span>{{ $social['youtube']['count'] ?? '—' }}</span>
                    </div>
                    <div class="follower-btn subscriber"><a href="{{ $social['youtube']['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ __('sidebar.social_subscribers') }}</a></div>
                </div>
            </div>
            <div class="col-6 instagram-Follower">
                <div class="social-box">
                    <div class="social">
                        <a href="{{ $social['instagram']['url'] ?? '#' }}" class="instagram-icon" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <span>{{ $social['instagram']['count'] ?? '—' }}</span>
                    </div>
                    <div class="follower-btn instagrams"><a href="{{ $social['instagram']['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ __('sidebar.social_followers') }}</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="widget post-widget mt-4">
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
                                        <img class="img-fluid" src="{{ $cover }}" alt="{{ $article->cover_alt ?? $article->title }}">
                                    </a>
                                @else
                                    <a href="{{ route('articles.show', $article->slug) }}" class="d-block bg-light ratio ratio-1x1 rounded"></a>
                                @endif
                            </div>
                            <div class="blog-post-details">
                                @if ($article->category)
                                    <a class="sidebar-post-category" href="{{ route('categories.show', $article->category->slug) }}">{{ $article->category->name }}</a>
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
