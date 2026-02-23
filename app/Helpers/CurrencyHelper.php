<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Setting;

class CurrencyHelper
{
    /**
     * Format a numeric value as currency using the platform settings.
     */
    public static function format(float|int|string $amount): string
    {
        $amount = (float) $amount;

        $symbol = Setting::get('currency_symbol', '$');
        $position = Setting::get('currency_position', 'left');
        $decimalFormat = Setting::get('decimal_format', 'dot');

        // Determine decimals and separator based on format
        match ($decimalFormat) {
            'none' => $formatted = number_format($amount, 0, '', ','),
            'comma' => $formatted = number_format($amount, 2, ',', '.'),
            default => $formatted = number_format($amount, 2, '.', ','), // dot
        };

        return match ($position) {
            'left_space' => "{$symbol} {$formatted}",
            'right' => "{$formatted}{$symbol}",
            'right_space' => "{$formatted} {$symbol}",
            default => "{$symbol}{$formatted}", // left
        };
    }
}
