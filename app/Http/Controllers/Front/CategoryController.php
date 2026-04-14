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

        $category = Category::where('slug', $slug)->firstOrFail();
        $articles = $category->articles()->published()->latest('published_at')->paginate(12);

        $sidebarCategories = Category::sidebarListWithPublishedCounts();

        return view('front.categories.show', compact('category', 'articles', 'sidebarCategories'));
    }
}
