<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Get locale from settings, fallback to config default
            $locale = \App\Models\Setting::get('site_language', config('app.locale'));

            if (in_array($locale, ['en', 'es', 'pt_BR'])) {
                app()->setLocale($locale);
                \Illuminate\Support\Carbon::setLocale($locale);
            }
        } catch (\Exception $e) {
            // Fallback to default if database/cache fails
            app()->setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
