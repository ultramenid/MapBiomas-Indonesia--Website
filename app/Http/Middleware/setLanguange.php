<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class setLanguange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = $request->route('lang');

        if (is_string($lang) && in_array($lang, ['id', 'en'], true)) {
            app()->setLocale($lang);
        } else {
            app()->setLocale(config('app.fallback_locale', 'en'));
        }

        return $next($request);
    }
}
