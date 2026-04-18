@extends('layouts.front')

@section('title', __('front.articles_title'))

@section('content')
    @include('front.partials.page-header', ['title' => __('front.articles_title')])

    <section class="space-ptb">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    @forelse ($articles as $article)
                        <x-article-card :article="$article" style="11" />
                    @empty
                        <p class="text-muted">{{ __('sidebar.no_recent_posts') }}</p>
                    @endforelse
                    <div class="mt-4">
                        {{ $articles->links() }}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar mt-lg-0">
                        @include('front.partials.sidebar-recent-posts', ['recentArticles' => $recentArticles])
                        @if (!empty($adSidebar))
                            <div class="widget mb-4">
                                @include('front.partials.ad-banner', ['ad' => $adSidebar, 'variant' => 'vertical'])
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
