<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Video;

class CategoryController extends Controller
{
    public function show(string $locale, string $slug)
    {
        $aliasMap = [
            'industrie' => 'industrie-story',
        ];

        if (isset($aliasMap[$slug])) {
            return redirect()->route('categories.show', [
                'locale' => $locale,
                'slug' => $aliasMap[$slug],
            ], 301);
        }

        $category = Category::query()
            ->with('children:id,parent_id')
            ->where('slug', $slug)
            ->firstOrFail();

        $categoryIds = [$category->id];
        if ($category->parent_id === null && $category->children->isNotEmpty()) {
            $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->all());
        }

        if ($category->slug === '2im-tv') {
            $playlistId = config('ivoireindustriemag.youtube.playlist_id');
            $playlistEmbedUrl = is_string($playlistId) && $playlistId !== ''
                ? 'https://www.youtube.com/embed/videoseries?list='.rawurlencode($playlistId)
                : null;

            $videos = Video::query()
                ->published()
                ->latest('is_featured')
                ->latest('published_at')
                ->paginate(12);

            $recentArticles = \App\Models\Article::query()
                ->published()
                ->whereNotIn('category_id', $categoryIds)
                ->latest('published_at')
                ->take(6)
                ->get();

            return view('front.categories.show-2im-tv', compact('category', 'videos', 'recentArticles', 'playlistEmbedUrl'));
        }

        $articles = \App\Models\Article::query()
            ->published()
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->paginate(12);

        $recentArticles = \App\Models\Article::query()
            ->published()
            ->whereNotIn('category_id', $categoryIds)
            ->latest('published_at')
            ->take(6)
            ->get();

        return view('front.categories.show', compact('category', 'articles', 'recentArticles'));
    }
}
