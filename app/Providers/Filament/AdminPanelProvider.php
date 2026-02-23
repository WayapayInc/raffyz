<?php

namespace App\Providers\Filament;

use Filament\Panel;
use App\Models\Setting;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use App\Filament\Widgets\SalesChartWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Widgets\DashboardOverviewWidget;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->brandName(fn() => Setting::get('platform_name'))
            ->brandLogo(fn() => Setting::get('logo_light') ? \Illuminate\Support\Facades\Storage::url(Setting::get('logo_light')) : null)
            ->darkModeBrandLogo(fn() => Setting::get('logo_dark') ? \Illuminate\Support\Facades\Storage::url(Setting::get('logo_dark')) : null)
            ->favicon(fn() => Setting::get('favicon') ? \Illuminate\Support\Facades\Storage::url(Setting::get('favicon')) : null)
            ->spa()
            ->colors([
                'primary' => Setting::get('primary_color'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->widgets([
                DashboardOverviewWidget::class,
                SalesChartWidget::class,
            ])
            ->middleware([
                \App\Http\Middleware\SetLocale::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                \App\Http\Middleware\LicenseMiddleware::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn(): string => (request()->routeIs('filament.admin.auth.login') ? '<style>.fi-logo { display: none; }</style>' : '') .
                    '<style>#nprogress .bar { background: ' . Setting::get('primary_color', '#7c3aed') . ' !important; } #nprogress .peg { box-shadow: 0 0 10px ' . Setting::get('primary_color', '#7c3aed') . ', 0 0 5px ' . Setting::get('primary_color', '#7c3aed') . ' !important; }</style>',
            );
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            $panel = \Filament\Facades\Filament::getCurrentPanel();

            if ($panel && $panel->getId() === 'admin') {
                if (config('demo.status') && ! $user->isSuperAdmin()) {
                    $restricted = [
                        'create',
                        'update',
                        'delete',
                        'restore',
                        'forceDelete',
                        'reorder',
                        'deleteAny',
                        'forceDeleteAny',
                    ];

                    if (in_array($ability, $restricted)) {
                        return \Illuminate\Auth\Access\Response::deny('Action not allowed in demo mode.');
                    }
                }
            }
        });
    }
}
