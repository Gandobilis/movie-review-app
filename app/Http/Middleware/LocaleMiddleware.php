<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Locale');
        $locales = config('app.locales');
        $fallbackLocale = config('app.fallback_locale');

        if ($locale && in_array($locale, $locales)) App::setLocale($locale);
        else App::setLocale($fallbackLocale);

        return $next($request);
    }
}
