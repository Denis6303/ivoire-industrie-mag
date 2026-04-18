@extends('layouts.front')

@section('title', e($category->name))
@section('meta_description', e(\Illuminate\Support\Str::limit(strip_tags($category->description ?? ''), 160)))

@section('content')
    @include('front.partials.page-header', [
        'title' => $category->name,
        'breadcrumbItems' => [['label' => 'Articles', 'url' => route('articles.index')]],
    ])

    <section class="space-ptb">
        <div class="container">
            @if ($category->description)
                <p class="lead mb-4">{{ $category->description }}</p>
            @endif
            <div class="row">
                <div class="col-lg-8">
                    @forelse ($articles as $article)
                        <x-article-card :article="$article" style="11" />
                    @empty
                        <p class="text-muted">Aucun article dans cette catégorie.</p>
                    @endforelse
                    {{ $articles->links() }}
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
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
