<?php

namespace App\Http\Middleware;

use App\Services\SiteVisitorTracker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisit
{
    public function __construct(
        private readonly SiteVisitorTracker $tracker
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        return $this->tracker->track($request, $response);
    }
}
