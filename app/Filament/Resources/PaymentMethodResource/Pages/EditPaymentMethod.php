<?php

declare(strict_types=1);

namespace App\Filament\Resources\PaymentMethodResource\Pages;

use App\Filament\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditPaymentMethod extends EditRecord
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        // Query the DB directly for the current logo BEFORE saving
        $current = PaymentMethod::query()
            ->where('id', $this->record->getKey())
            ->value('logo');

        if ($current) {
            $this->oldLogoPath = $current;
        }
    }

    /**
     * Public so it persists across Livewire requests.
     */
    public ?string $oldLogoPath = null;

    protected function afterSave(): void
    {
        $newLogo = $this->record->fresh()->logo;

        if ($this->oldLogoPath && $this->oldLogoPath !== $newLogo) {
            Storage::disk('public')->delete($this->oldLogoPath);
            $this->oldLogoPath = null;
        }
    }
}
