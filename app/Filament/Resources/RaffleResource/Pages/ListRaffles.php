<?php

declare(strict_types=1);

namespace App\Filament\Resources\RaffleResource\Pages;

use App\Filament\Resources\RaffleResource;
use Filament\Resources\Pages\ListRecords;

class ListRaffles extends ListRecords
{
    protected static string $resource = RaffleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
