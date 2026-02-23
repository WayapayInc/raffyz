<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\TransactionStatus;
use App\Helpers\CurrencyHelper;
use App\Models\Raffle;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('messages.total_raffles'), Raffle::count())
                ->icon('heroicon-o-ticket')
                ->color('primary'),

            Stat::make(__('messages.active_raffles'), Raffle::active()->count())
                ->icon('heroicon-o-play')
                ->color('success'),

            Stat::make(__('messages.total_transactions'), Transaction::count())
                ->icon('heroicon-o-banknotes')
                ->color('info'),

            Stat::make(__('messages.pending_transactions'), Transaction::where('status', TransactionStatus::Pending)->count())
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make(__('messages.total_revenue'), CurrencyHelper::format((float) Transaction::where('status', TransactionStatus::Confirmed)->sum('total_amount')))
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make(__('messages.total_users'), User::count())
                ->icon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
