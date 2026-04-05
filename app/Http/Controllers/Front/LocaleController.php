<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        $supported = config('ivoireindustriemag.supported_locales', ['fr', 'en']);
        abort_unless(in_array($locale, $supported, true), 404);

        session(['locale' => $locale]);

        return redirect()->back();
    }
}
