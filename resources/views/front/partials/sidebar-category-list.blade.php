@php
    $title = $title ?? __('sidebar.categories_widget_title');
    $list = isset($excludeCategoryId)
        ? $categories->where('id', '!=', $excludeCategoryId)->values()
        : $categories;
@endphp
<div class="widget sidebar-category">
    <h6 class="widget-title">{{ $title }}</h6>
    <ul class="sidebar-category-simple-list list-unstyled mb-0">
        @forelse ($list as $cat)
            <li>
                <a href="{{ route('categories.show', $cat->slug) }}" class="sidebar-category-simple-link">
                    <span class="sidebar-category-simple-name">{{ $cat->name }}</span>
                    <span class="sidebar-category-simple-count">{{ $cat->published_articles_count }}</span>
                </a>
            </li>
        @empty
            <li class="text-muted small py-2">{{ __('sidebar.no_categories') }}</li>
        @endforelse
    </ul>
</div>
