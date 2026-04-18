<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;

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
