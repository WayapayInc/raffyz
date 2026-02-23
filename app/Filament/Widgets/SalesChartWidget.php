<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\TransactionStatus;
use App\Helpers\CurrencyHelper;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'day';

    public function getHeading(): ?string
    {
        return __('messages.tickets_sold');
    }

    protected function getFilters(): ?array
    {
        return [
            'day' => __('messages.filter_day'),
            'week' => __('messages.filter_week'),
            'month' => __('messages.filter_month'),
            'year' => __('messages.filter_year'),
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $labels = [];
        $data = [];

        if ($activeFilter === 'day') {
            $startOfDay = Carbon::now()->startOfDay();
            for ($i = 0; $i < 24; $i++) {
                $date = $startOfDay->copy()->addHours($i);
                $labels[] = $date->format('H:i');
                $data[] = Transaction::where('status', TransactionStatus::Confirmed)
                    ->whereBetween('created_at', [
                        $date->copy()->startOfHour(),
                        $date->copy()->endOfHour()
                    ])
                    ->sum('tickets_quantity');
            }
        } elseif ($activeFilter === 'week') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->translatedFormat('D d');
                $data[] = Transaction::where('status', TransactionStatus::Confirmed)
                    ->whereBetween('created_at', [
                        $date->copy()->startOfDay(),
                        $date->copy()->endOfDay()
                    ])
                    ->sum('tickets_quantity');
            }
        } elseif ($activeFilter === 'month') {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->translatedFormat('d M');
                $data[] = Transaction::where('status', TransactionStatus::Confirmed)
                    ->whereBetween('created_at', [
                        $date->copy()->startOfDay(),
                        $date->copy()->endOfDay()
                    ])
                    ->sum('tickets_quantity');
            }
        } else {
            // Year
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->translatedFormat('M Y');
                $data[] = Transaction::where('status', TransactionStatus::Confirmed)
                    ->whereBetween('created_at', [
                        $date->copy()->startOfMonth(),
                        $date->copy()->endOfMonth()
                    ])
                    ->sum('tickets_quantity');
            }
        }

        return [
            'datasets' => [
                [
                    'label' => __('messages.tickets_sold'),
                    'data' => $data,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.15)', // Green-500
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                    'pointBackgroundColor' => 'rgb(34, 197, 94)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
