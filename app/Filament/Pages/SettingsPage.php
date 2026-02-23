<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class SettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Settings';

    public static function getNavigationLabel(): string
    {
        return __('messages.settings');
    }

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return __('messages.settings');
    }

    protected static ?int $navigationSort = 7;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-cog-6-tooth';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && $user->role === 'admin';
    }

    protected string $view = 'filament.pages.settings-page';

    public ?array $data = [];

    private array $settingKeys = [
        'platform_name',
        'logo_light',
        'logo_dark',
        'favicon',
        'contact_email',
        'contact_whatsapp',
        'contact_telegram',
        'footer_logos',
        'social_facebook',
        'social_instagram',
        'social_twitter',
        'social_tiktok',
        'primary_color',
        'currency_code',
        'currency_symbol',
        'currency_position',
        'decimal_format',
        'site_language',
        'default_theme',
        'max_purchase_tickets',
    ];

    private array $envKeys = [
        'MAIL_FROM_ADDRESS',
        'MAIL_MAILER',
        'MAIL_HOST',
        'MAIL_PORT',
        'MAIL_USERNAME',
        'MAIL_PASSWORD',
        'MAIL_ENCRYPTION',
        'TIMEZONE',
    ];

    private function getTimezones(): array
    {
        $timezonesPath = public_path('assets/settings/timezones.json');

        if (!file_exists($timezonesPath)) {
            return [];
        }

        $json = file_get_contents($timezonesPath);
        $data = json_decode($json, true);

        if (!$data) {
            return [];
        }

        $options = [];

        foreach ($data as $countryCode => $country) {
            $groupLabel = $country['name'];
            $groupOptions = [];

            if (isset($country['timezones'])) {
                foreach ($country['timezones'] as $key => $info) {
                    $label = $info['name'] . ' (' . $info['abbr'] . ')';
                    $groupOptions[$key] = $label;
                }
            }

            if (!empty($groupOptions)) {
                $options[$groupLabel] = $groupOptions;
            }
        }

        return $options;
    }

    public function mount(): void
    {
        $data = [];

        foreach ($this->settingKeys as $key) {
            $data[$key] = Setting::get($key, '');
        }

        if (isset($data['footer_logos']) && is_string($data['footer_logos'])) {
            $data['footer_logos'] = json_decode($data['footer_logos'], true);
        }

        foreach ($this->envKeys as $key) {
            $value = env($key);
            if ($key === 'MAIL_ENCRYPTION' && is_null($value)) {
                $value = '';
            }
            $data[$key] = $value;
        }

        $this->getSchema('form')->fill($data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make(__('messages.general_settings'))
                    ->schema([
                        Forms\Components\TextInput::make('platform_name')
                            ->label(__('messages.platform_name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\ColorPicker::make('primary_color')
                            ->label(__('messages.primary_color')),

                        Forms\Components\Select::make('site_language')
                            ->label(__('messages.language'))
                            ->options([
                                'en' => 'English',
                                'es' => 'Español',
                                'pt_BR' => 'Português (Brasil)',
                            ])
                            ->default('en'),

                        Forms\Components\Select::make('default_theme')
                            ->label(__('messages.default_theme'))
                            ->options([
                                'light' => __('messages.light_mode'),
                                'dark' => __('messages.dark_mode'),
                                'system' => __('messages.system_user'),
                            ])
                            ->default('system'),

                        Forms\Components\Select::make('max_purchase_tickets')
                            ->label(__('messages.max_purchase_tickets'))
                            ->options([
                                '10' => '10',
                                '20' => '20',
                                '30' => '30',
                                '40' => '40',
                                '50' => '50',
                                '100' => '100',
                            ])
                            ->default('50'),
                    ])->columns(3),

                \Filament\Schemas\Components\Section::make(__('messages.currency_section'))
                    ->schema([
                        Forms\Components\TextInput::make('currency_code')
                            ->label(__('messages.currency_code'))
                            ->placeholder('USD, EUR, VES...')
                            ->maxLength(10),

                        Forms\Components\TextInput::make('currency_symbol')
                            ->label(__('messages.currency_symbol'))
                            ->placeholder('$, €, Bs...')
                            ->maxLength(10),

                        Forms\Components\Select::make('currency_position')
                            ->label(__('messages.currency_position'))
                            ->options([
                                'left' => __('messages.currency_pos_left'),
                                'left_space' => __('messages.currency_pos_left_space'),
                                'right' => __('messages.currency_pos_right'),
                                'right_space' => __('messages.currency_pos_right_space'),
                            ])
                            ->default('left'),

                        Forms\Components\Select::make('decimal_format')
                            ->label(__('messages.decimal_format'))
                            ->options([
                                'none' => __('messages.decimal_fmt_none'),
                                'dot' => __('messages.decimal_fmt_dot'),
                                'comma' => __('messages.decimal_fmt_comma'),
                            ])
                            ->default('dot'),
                    ])->columns(4),

                \Filament\Schemas\Components\Section::make(__('messages.logos'))
                    ->schema([
                        Forms\Components\FileUpload::make('logo_light')
                            ->disabled(fn() => config('demo.status') && ! auth()->user()?->isSuperAdmin())
                            ->label(__('messages.logo_light'))
                            ->image()
                            ->disk('default')
                            ->directory('assets/settings'),

                        Forms\Components\FileUpload::make('logo_dark')
                            ->disabled(fn() => config('demo.status') && ! auth()->user()?->isSuperAdmin())
                            ->label(__('messages.logo_dark'))
                            ->image()
                            ->disk('default')
                            ->directory('assets/settings'),

                        Forms\Components\FileUpload::make('favicon')
                            ->disabled(fn() => config('demo.status') && ! auth()->user()?->isSuperAdmin())
                            ->label(__('messages.favicon'))
                            ->image()
                            ->disk('default')
                            ->directory('assets/settings'),
                    ])->columns(3),

                \Filament\Schemas\Components\Section::make(__('messages.contact_info'))
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')
                            ->label(__('messages.email'))
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contact_whatsapp')
                            ->label(__('messages.whatsapp'))
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contact_telegram')
                            ->label(__('messages.telegram'))
                            ->maxLength(255),
                    ])->columns(3),

                \Filament\Schemas\Components\Section::make(__('messages.social_media'))
                    ->schema([
                        Forms\Components\TextInput::make('social_facebook')
                            ->label(__('messages.facebook'))
                            ->url()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('social_instagram')
                            ->label(__('messages.instagram'))
                            ->url()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('social_twitter')
                            ->label(__('messages.twitter'))
                            ->url()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('social_tiktok')
                            ->label(__('messages.tiktok'))
                            ->url()
                            ->maxLength(255),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make(__('messages.footer'))
                    ->schema([
                        Forms\Components\FileUpload::make('footer_logos')
                            ->disabled(fn() => config('demo.status') && ! auth()->user()?->isSuperAdmin())
                            ->label(__('messages.footer_logos'))
                            ->multiple()
                            ->image()
                            ->disk('default')
                            ->directory('assets/settings')
                            ->columnSpanFull(),
                    ]),

                \Filament\Schemas\Components\Section::make(__('messages.email_settings'))
                    ->schema([
                        Forms\Components\TextInput::make('MAIL_FROM_ADDRESS')
                            ->label(__('messages.email_no_reply'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('MAIL_MAILER')
                            ->label(__('messages.email_driver'))
                            ->options([
                                'log' => __('messages.email_driver_log'),
                                'sendmail' => __('messages.email_driver_sendmail'),
                                'smtp' => __('messages.email_driver_smtp'),
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('MAIL_HOST')
                            ->label(__('messages.host'))
                            ->maxLength(255),

                        Forms\Components\TextInput::make('MAIL_PORT')
                            ->label(__('messages.port'))
                            ->numeric(),

                        Forms\Components\TextInput::make('MAIL_USERNAME')
                            ->label(__('messages.username'))
                            ->maxLength(255),

                        Forms\Components\TextInput::make('MAIL_PASSWORD')
                            ->label(__('messages.password'))
                            ->password()
                            ->revealable()
                            ->maxLength(255),

                        Forms\Components\Select::make('MAIL_ENCRYPTION')
                            ->label(__('messages.encryption'))
                            ->options([
                                '' => __('messages.none'),
                                'tls' => __('messages.tls'),
                                'ssl' => __('messages.ssl'),
                            ]),

                        Forms\Components\Select::make('TIMEZONE')
                            ->label(__('messages.default_timezone'))
                            ->options($this->getTimezones())
                            ->searchable()
                            ->required(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        if (config('demo.status') && ! auth()->user()->isSuperAdmin()) {
            Notification::make()
                ->title('Action not allowed in demo mode')
                ->danger()
                ->send();

            return;
        }

        $data = $this->getSchema('form')->getState();

        foreach ($data as $key => $value) {
            if (in_array($key, $this->settingKeys)) {
                Setting::set($key, is_array($value) ? json_encode($value) : $value);
            }

            if (in_array($key, $this->envKeys)) {
                \App\Helpers\SystemHelper::updateEnvFile($key, $value);
            }
        }

        Notification::make()
            ->title(__('messages.settings_saved'))
            ->success()
            ->send();
    }
}
