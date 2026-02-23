<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\RaffleStatus;
use App\Filament\Resources\RaffleResource\Pages;
use App\Helpers\CurrencyHelper;
use App\Models\Raffle;
use App\Models\Setting;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class RaffleResource extends Resource
{
    protected static ?string $model = Raffle::class;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('messages.raffles');
    }

    public static function getModelLabel(): string
    {
        return __('messages.raffle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.raffles');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-gift';
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
                \Filament\Schemas\Components\Section::make(__('messages.raffle_details'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('messages.title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($set, ?string $state): void {
                                if ($state) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label(__('messages.slug'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\RichEditor::make('description')
                            ->label(__('messages.description'))
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('end_date')
                            ->label(__('messages.end_date'))
                            ->required()
                            ->minDate(now())
                            ->native(false),

                        Forms\Components\TextInput::make('ticket_price')
                            ->label(__('messages.ticket_price'))
                            ->required()
                            ->numeric()
                            ->prefix(Setting::get('currency_symbol', '$'))
                            ->minValue(0.01),

                        Forms\Components\TextInput::make('total_tickets')
                            ->label(__('messages.total_tickets'))
                            ->required()
                            ->numeric()
                            ->minValue(100)

                            ->maxValue(100000),

                        Forms\Components\TextInput::make('minimum_purchase_ticket')
                            ->label(__('messages.minimum_purchase_ticket')) // Ensure translation key exists or use string
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label(__('messages.status'))
                            ->options(RaffleStatus::class)
                            ->default(RaffleStatus::Active)
                            ->required(),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make(__('messages.images'))
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label(__('messages.images'))
                            ->multiple()
                            ->image()
                            ->disk('default')
                            ->directory('assets/raffles')
                            ->reorderable()
                            ->required()
                            ->minFiles(1)
                            ->maxFiles(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label(__('messages.image'))
                    ->circular()
                    ->stacked()
                    ->limit(2),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('messages.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('effective_status')
                    ->label(__('messages.status'))
                    ->badge()
                    ->color(fn(RaffleStatus $state): string => $state->getColor()),

                Tables\Columns\TextColumn::make('ticket_price')
                    ->label(__('messages.ticket_price'))
                    ->formatStateUsing(fn($state) => CurrencyHelper::format((float) $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('tickets_sold')
                    ->label(__('messages.sold'))
                    ->formatStateUsing(fn(Raffle $record): string => "{$record->tickets_sold} / {$record->total_tickets}")
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label(__('messages.end_date'))
                    ->dateTime()
                    ->color(fn($record) => $record->end_date->isPast() ? 'danger' : null)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(RaffleStatus::class)
                    ->query(function (Builder $query, array $data): Builder {
                        if (! $data['value']) {
                            return $query;
                        }

                        $status = $data['value'];

                        if ($status === RaffleStatus::Active->value) {
                            return $query->active();
                        }

                        if ($status === RaffleStatus::Finished->value) {
                            return $query->finished();
                        }

                        return $query->where('status', $status);
                    }),
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
            'index' => Pages\ListRaffles::route('/'),
            'create' => Pages\CreateRaffle::route('/create'),
            'edit' => Pages\EditRaffle::route('/{record}/edit'),
        ];
    }
}
