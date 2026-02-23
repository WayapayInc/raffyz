<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstalledMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If installed file DOES NOT exist, redirect to installer.
        if (!file_exists(storage_path('installed'))) {
             // Allow 'install' route and ANY 'livewire' internal routes
             if (!$request->routeIs('install') && !str_starts_with($request->path(), 'livewire')) {
                 return redirect()->route('install');
             }
        }

        return $next($request);
    }
}
