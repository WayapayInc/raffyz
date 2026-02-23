<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string
    {
        return __('messages.pages');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.pages');
    }

    public static function getModelLabel(): string
    {
        return __('messages.page');
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-document-text';
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
                \Filament\Schemas\Components\Section::make(__('messages.page_details'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('messages.title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Set $set, ?string $state): void {
                                if ($state) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label(__('messages.slug'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\RichEditor::make('content')
                            ->label(__('messages.content'))
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('messages.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('messages.slug'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('messages.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
