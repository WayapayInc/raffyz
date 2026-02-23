<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RaffleStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Raffle extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'end_date',
        'ticket_price',
        'total_tickets',
        'minimum_purchase_ticket',
        'tickets_sold',
        'images',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'status' => RaffleStatus::class,
            'end_date' => 'datetime',
            'ticket_price' => 'decimal:2',
            'total_tickets' => 'integer',
            'minimum_purchase_ticket' => 'integer',
            'tickets_sold' => 'integer',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', RaffleStatus::Active)
            ->where('end_date', '>', now());
    }

    public function scopeFinished(Builder $query): Builder
    {
        return $query->where(
            fn(Builder $q) =>
            $q->where('status', RaffleStatus::Finished)
                ->orWhere(
                    fn(Builder $q2) =>
                    $q2->where('status', RaffleStatus::Active)
                        ->where('end_date', '<=', now())
                )
        );
    }

    protected function ticketsRemaining(): Attribute
    {
        return Attribute::make(
            get: fn(): int => $this->total_tickets - $this->tickets_sold,
        );
    }

    protected function ticketsAvailable(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                $pendingAmount = $this->transactions()
                    ->where('status', \App\Enums\TransactionStatus::Pending)
                    ->sum('tickets_quantity');

                return max(0, $this->tickets_remaining - (int) $pendingAmount);
            },
        );
    }

    protected function soldPercentage(): Attribute
    {
        return Attribute::make(
            get: fn(): float => $this->total_tickets > 0
                ? round(($this->tickets_sold / $this->total_tickets) * 100, 1)
                : 0,
        );
    }

    protected function effectiveStatus(): Attribute
    {
        return Attribute::make(
            get: function (): RaffleStatus {
                if ($this->status === RaffleStatus::Active && $this->end_date->isPast()) {
                    return RaffleStatus::Finished;
                }
                return $this->status;
            },
        );
    }

    public function isActive(): bool
    {
        return $this->effective_status === RaffleStatus::Active;
    }

    public function getUrl(): string
    {
        return route('raffles.show', $this->slug);
    }
}
