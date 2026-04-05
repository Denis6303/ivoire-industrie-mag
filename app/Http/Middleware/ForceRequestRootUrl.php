<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForceRequestRootUrl
{
    /**
     * Aligne route() / url() / asset() (sans ASSET_URL) sur l’URL réelle du navigateur
     * (127.0.0.1 vs localhost, port, sous-dossier public sous WAMP).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $assetUrl = config('app.asset_url');

        if ($assetUrl === null || $assetUrl === '') {
            URL::forceRootUrl(rtrim($request->root(), '/'));
        }

        return $next($request);
    }
}
