<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\IndustrySector;

class SectorController extends Controller
{
    public function index()
    {
        $sectors = IndustrySector::where('is_active', true)->orderBy('order')->paginate(12);
        return view('front.sectors.index', compact('sectors'));
    }

    public function show(string $slug)
    {
        $sector = IndustrySector::where('slug', $slug)->firstOrFail();
        $articles = $sector->articles()->published()->latest('published_at')->paginate(12);

        return view('front.sectors.show', compact('sector', 'articles'));
    }
}
