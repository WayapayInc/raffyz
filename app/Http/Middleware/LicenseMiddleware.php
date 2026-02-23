<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LicenseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the current route is the license page or auth routes to avoid infinite loop
        if ($request->routeIs('filament.admin.pages.license') || $request->routeIs('filament.admin.auth.*')) {
            return $next($request);
        }

        // Check if license settings are missing or empty
        $licenseKey = Setting::get('license_key');
        $licenseType = Setting::get('license_type');

        if (empty($licenseKey) || empty($licenseType)) {
            return redirect()->route('filament.admin.pages.license');
        }

        return $next($request);
    }
}
