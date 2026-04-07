<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        $locale = (string) ($request->route('locale')
            ?? $request->segment(1)
            ?? config('app.locale', 'fr'));

        if ($request->is($locale.'/admin') || $request->is($locale.'/admin/*')) {
            return route('admin.login', ['locale' => $locale]);
        }

        return route('login', ['locale' => $locale]);
    }
}
