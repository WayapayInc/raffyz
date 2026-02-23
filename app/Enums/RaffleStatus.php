<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RaffleStatus: string implements HasLabel
{
    case Active = 'active';
    case Finished = 'finished';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => __('messages.active'),
            self::Finished => __('messages.finished'),
            self::Cancelled => __('messages.cancelled'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Finished => 'danger',
            self::Cancelled => 'danger',
        };
    }
}
