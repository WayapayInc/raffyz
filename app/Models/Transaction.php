<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Transaction extends Model
{
    protected $fillable = [
        'raffle_id',
        'payment_method_id',
        'full_name',
        'identity_document',
        'email',
        'phone',
        'reference_number',
        'tickets_quantity',
        'total_amount',
        'rate_applied',
        'amount_charged',
        'tickets_bought',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tickets_bought' => 'array',
            'status' => TransactionStatus::class,
            'total_amount' => 'decimal:2',
            'tickets_quantity' => 'integer',
        ];
    }

    public function raffle(): BelongsTo
    {
        return $this->belongsTo(Raffle::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function isPending(): bool
    {
        return $this->status === TransactionStatus::Pending;
    }

    public function isConfirmed(): bool
    {
        return $this->status === TransactionStatus::Confirmed;
    }
}
