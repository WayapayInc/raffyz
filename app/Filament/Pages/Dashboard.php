<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    public static function getNavigationLabel(): string
    {
        return __('messages.dashboard');
    }

    public function getTitle(): string | Htmlable
    {
        return __('messages.dashboard');
    }

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.pages.dashboard-header');
    }
}
