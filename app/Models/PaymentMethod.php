<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

final class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'instructions',
        'logo',
        'currency_code',
        'is_active',
        'exchange_rate',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'exchange_rate' => 'decimal:2',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
