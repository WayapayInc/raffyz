<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;

class LicenseSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $slug = 'license';

    protected string $view = 'filament.pages.license-settings';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        if (Setting::get('license_key')) {
            $this->redirect(route('filament.admin.pages.dashboard'), navigate: true);
        }

        $this->form->fill([
            'license_key' => Setting::get('license_key'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Verify Your License')
                    ->description('Please enter your license key to activate the platform.')
                    ->components([
                        TextInput::make('license_key')
                            ->label('License Key')
                            ->required()
                            ->minLength(10)
                            ->placeholder('Enter your license key here...'),
                    ]),
            ])
            ->statePath('data');
    }

    public function verify(): void
    {
        if (config('demo.status') && ! auth()->user()->isSuperAdmin()) {
            Notification::make()
                ->title('Action not allowed in demo mode')
                ->danger()
                ->send();

            return;
        }

        $data = $this->form->getState();
        $licenseKey = $data['license_key'];

        try {
            $response = Http::withHeaders([
                'Referer' => url('/'),
            ])->post('https://miguelvasquez.net/api/license/' . urlencode($licenseKey));

            $responseData = $response->json();

            if ($response->successful()) {
                if ($responseData['success']) {
                    // Update settings
                    Setting::set('license_key', $responseData['data']['license_key']);
                    Setting::set('license_type', $responseData['data']['license_type']);

                    Notification::make()
                        ->title($responseData['message'])
                        ->success()
                        ->send();

                    $this->redirect(route('filament.admin.pages.dashboard'), navigate: true);
                } else {
                    Notification::make()
                        ->title($responseData['message'] ?? 'Invalid license')
                        ->danger()
                        ->send();

                    $this->form->fill(['license_key' => '']);
                }
            } else {
                Notification::make()
                    ->title($responseData['message'] ?? 'Failed to connect to license server')
                    ->danger()
                    ->send();

                $this->form->fill(['license_key' => '']);
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Connection Error')
                ->body('Check you internet connection')
                ->danger()
                ->send();

            $this->form->fill(['license_key' => '']);
        }
    }
}
