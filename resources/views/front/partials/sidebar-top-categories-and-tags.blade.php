<div class="widget sidebar-top-category-posts mt-4">
    <h6 class="widget-title">{{ __('sidebar.top_category_posts') }}</h6>
    <div class="sidebar-home-posts">
        @forelse ($topCategoryPosts as $row)
            @php
                $article = $row['article'];
                $category = $row['category'];
                $cover = article_cover($article->cover_image);
            @endphp
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
                    <a class="sidebar-post-category" href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>
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
            <p class="text-muted small mb-0">{{ __('sidebar.no_category_posts') }}</p>
        @endforelse
    </div>
</div>

<div class="widget widget-tag mt-4">
    <h6 class="widget-title">{{ __('sidebar.tags') }}</h6>
    @if ($sidebarTags->isEmpty())
        <p class="text-muted small mb-0">{{ __('sidebar.no_tags') }}</p>
    @else
        <ul>
            @foreach ($sidebarTags as $tag)
                <li>
                    <a href="{{ route('search', ['q' => $tag->name]) }}">{{ $tag->name }}<span>{{ $tag->articles_count }}</span></a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
