<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TransactionStatus: string implements HasLabel
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Rejected = 'rejected';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('messages.pending'),
            self::Confirmed => __('messages.confirmed'),
            self::Rejected => __('messages.rejected'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Confirmed => 'success',
            self::Rejected => 'danger',
        };
    }
}
