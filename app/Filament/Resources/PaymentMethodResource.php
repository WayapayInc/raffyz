<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\PaymentMethod;
use Filament\Resources\Resource;
use App\Filament\Resources\PaymentMethodResource\Pages;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('messages.payment_methods');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.payment_methods');
    }

    public static function getModelLabel(): string
    {
        return __('messages.payment_method');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-credit-card';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && $user->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make(__('messages.payment_method_details'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('messages.name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\RichEditor::make('instructions')
                            ->label(__('messages.instructions'))
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('logo')
                            ->label(__('messages.logo'))
                            ->image()
                            ->disk('default')
                            ->directory('assets/payment-methods'),

                        Forms\Components\TextInput::make('currency_code')
                            ->label(__('messages.currency_code'))
                            ->default('USD')
                            ->required()
                            ->maxLength(3),

                        Forms\Components\TextInput::make('exchange_rate')
                            ->label(__('messages.exchange_rate'))
                            ->numeric()
                            ->default(1.0000)
                            ->minValue(0.0001)
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label(__('messages.active'))
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label(__('messages.logo'))
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency_code')
                    ->label(__('messages.currency_code'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('exchange_rate')
                    ->label(__('messages.exchange_rate'))
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->disabled(fn() => config('demo.status') && ! auth()->user()?->isSuperAdmin())
                    ->label(__('messages.status'))
                    ->afterStateUpdated(function () {
                        \Filament\Notifications\Notification::make()
                            ->title(__('messages.status_updated'))
                            ->success()
                            ->send();
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
