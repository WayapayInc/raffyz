<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Actions\ConfirmTransactionAction;
use App\Actions\RejectTransactionAction;
use App\Enums\TransactionStatus;
use App\Filament\Resources\TransactionResource\Pages;
use App\Helpers\CurrencyHelper;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $slug = 'tickets-sold';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('messages.tickets_sold');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.tickets_sold');
    }

    public static function getModelLabel(): string
    {
        return __('messages.transaction');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-ticket';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make(__('messages.transaction_details'))
                    ->schema([
                        Forms\Components\Select::make('raffle_id')
                            ->label(__('messages.raffle'))
                            ->relationship('raffle', 'title')
                            ->disabled(),

                        Forms\Components\Select::make('payment_method_id')
                            ->label(__('messages.payment_method'))
                            ->relationship('paymentMethod', 'name')
                            ->disabled(),

                        Forms\Components\TextInput::make('full_name')
                            ->label(__('messages.full_name'))
                            ->disabled(),

                        Forms\Components\TextInput::make('identity_document')
                            ->label(__('messages.identity_document'))
                            ->disabled(),

                        Forms\Components\TextInput::make('email')
                            ->label(__('messages.email'))
                            ->disabled(),

                        Forms\Components\TextInput::make('phone')
                            ->label(__('messages.phone'))
                            ->disabled(),

                        Forms\Components\TextInput::make('reference_number')
                            ->label(__('messages.reference_number'))
                            ->disabled(),

                        Forms\Components\TextInput::make('tickets_quantity')
                            ->label(__('messages.tickets_quantity'))
                            ->disabled(),

                        Forms\Components\TextInput::make('total_amount')
                            ->label(__('messages.total_amount'))
                            ->formatStateUsing(fn ($state) => CurrencyHelper::format((float) $state))
                            ->disabled(),

                        Forms\Components\TextInput::make('rate_applied')
                            ->label(__('messages.exchange_rate'))

                            ->disabled(),

                        Forms\Components\TextInput::make('amount_charged')
                            ->label(__('messages.amount_charged'))
                            ->formatStateUsing(fn ($record) => number_format((float) $record->amount_charged, 2) . ' ' . ($record->paymentMethod?->currency_code ?? ''))

                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label(__('messages.status'))
                            ->options(TransactionStatus::class)
                            ->disabled(),

                        Forms\Components\TagsInput::make('tickets_bought')
                            ->label(__('messages.ticket_numbers'))
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('raffle.title')
                    ->label(__('messages.raffle'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('messages.full_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('identity_document')
                    ->label(__('messages.identity_document'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('tickets_quantity')
                    ->label(__('messages.qty'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label(__('messages.total_amount'))
                    ->formatStateUsing(fn ($state) => CurrencyHelper::format((float) $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('rate_applied')
                    ->label(__('messages.rate')),


                Tables\Columns\TextColumn::make('amount_charged')
                    ->label(__('messages.charged'))
                    ->formatStateUsing(fn ($record) => number_format((float) $record->amount_charged, 2) . ' ' . ($record->paymentMethod?->currency_code ?? ''))
                    ->sortable(),


                Tables\Columns\TextColumn::make('reference_number')
                    ->label(__('messages.reference'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('messages.status'))
                    ->badge()
                    ->color(fn (TransactionStatus $state): string => $state->getColor()),

                Tables\Columns\TextColumn::make('tickets_bought')
                    ->label(__('messages.ticket_numbers'))
                    ->searchable(query: function ($query, string $search): Builder {
                        return $query->whereRaw('JSON_CONTAINS(tickets_bought, ?)', [json_encode((int) $search)]);
                    })
                    ->formatStateUsing(function ($state) {
                        $stateArray = is_array($state) ? $state : [$state];
                        $count = count($stateArray);

                        if ($count > 10) {
                            return implode(', ', array_slice($stateArray, 0, 10)) . ' ... (+' . ($count - 10) . ')';
                        }

                        return implode(', ', $stateArray);
                    })
                    ->default(__('messages.not_applicable'))
                    ->color(fn ($state) => empty($state) ? 'gray' : null),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(TransactionStatus::class),

                Tables\Filters\SelectFilter::make('raffle_id')
                    ->label(__('messages.raffle'))
                    ->relationship('raffle', 'title'),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),

                \Filament\Actions\Action::make('confirm')
                    ->label(__('messages.confirm'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(__('messages.confirm_transaction'))
                    ->modalDescription(__('messages.confirm_transaction_description'))
                    ->visible(fn (Transaction $record): bool => $record->isPending())
                    ->action(function (Transaction $record): void {
                        try {
                            (new ConfirmTransactionAction())->execute($record);

                            Notification::make()
                                ->title(__('messages.transaction_confirmed'))
                                ->success()
                                ->send();
                        } catch (\RuntimeException $e) {
                            Notification::make()
                                ->title(__('messages.error'))
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                \Filament\Actions\Action::make('reject')
                    ->label(__('messages.reject'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(__('messages.reject_transaction'))
                    ->modalDescription(__('messages.reject_transaction_description'))
                    ->visible(fn (Transaction $record): bool => $record->isPending())
                    ->action(function (Transaction $record): void {
                        try {
                            (new RejectTransactionAction())->execute($record);

                            Notification::make()
                                ->title(__('messages.transaction_rejected'))
                                ->success()
                                ->send();
                        } catch (\RuntimeException $e) {
                            Notification::make()
                                ->title(__('messages.error'))
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'view' => Pages\ViewTransaction::route('/{record}'),
        ];
    }
}
