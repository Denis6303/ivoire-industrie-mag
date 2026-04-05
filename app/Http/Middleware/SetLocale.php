<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('ivoireindustriemag.supported_locales', ['fr', 'en']);
        $fromUrl = $request->route('locale');
        if (is_string($fromUrl) && in_array($fromUrl, $supported, true)) {
            $locale = $fromUrl;
            session(['locale' => $locale]);
        } else {
            $locale = session('locale', config('app.locale'));
            if (! in_array($locale, $supported, true)) {
                $locale = config('app.locale');
            }
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
