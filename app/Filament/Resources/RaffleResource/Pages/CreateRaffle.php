<?php

declare(strict_types=1);

namespace App\Filament\Resources\RaffleResource\Pages;

use App\Filament\Resources\RaffleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRaffle extends CreateRecord
{
    protected static string $resource = RaffleResource::class;
}
