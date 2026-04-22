<?php

namespace App\Http\Middleware;

use App\Models\Article;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackArticleView
{
    /**
     * Bots / crawlers courants à ignorer.
     * Liste non exhaustive mais couvre l'essentiel (Googlebot, Bingbot, etc.).
     */
    private const BOT_PATTERNS = [
        'bot', 'crawl', 'slurp', 'spider', 'mediapartners', 'curl',
        'wget', 'python', 'java/', 'libwww', 'httpclient',
        'facebookexternalhit', 'linkedinbot', 'twitterbot', 'whatsapp',
        'applebot', 'yandex', 'baidu', 'duckduckbot', 'semrush',
        'ahrefsbot', 'mj12bot', 'dotbot', 'petalbot',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Compter seulement les GET (pas les POST/ajax)
        if (! $request->isMethod('GET')) {
            return $response;
        }

        // Ne pas compter les admins / utilisateurs connectés
        if (auth()->check()) {
            return $response;
        }

        // Ne pas compter les bots/crawlers
        $userAgent = strtolower($request->userAgent() ?? '');
        foreach (self::BOT_PATTERNS as $pattern) {
            if (str_contains($userAgent, $pattern)) {
                return $response;
            }
        }

        $slug = $request->route('slug');
        if (! $slug) {
            return $response;
        }

        // Dédoublonnage par session : 1 vue par article toutes les 4 h max
        $sessionKey = 'viewed_article_' . md5($slug);
        if (session()->has($sessionKey)) {
            return $response;
        }

        // Incrémenter UNIQUEMENT le view_count (sans toucher à updated_at)
        Article::where('slug', $slug)
            ->whereNull('deleted_at')
            ->toBase()                    // requête brute pour éviter les events Eloquent
            ->update(['view_count' => \DB::raw('view_count + 1')]);

        // Mémoriser dans la session pour 4 heures
        session()->put($sessionKey, true);
        session()->push('_view_ttls', [$sessionKey => now()->addHours(4)->timestamp]);

        return $response;
    }
}
