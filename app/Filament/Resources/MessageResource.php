<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Models\Message;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid as InfoGrid;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Inbox';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::unread()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Inquiry Details')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->disabled(),

                        TextInput::make('email')
                            ->disabled(),
                    ]),

                    TextInput::make('subject')
                        ->disabled(),

                    DateTimePicker::make('read_at')
                        ->disabled(),

                    Textarea::make('body')
                        ->rows(8)
                        ->disabled()
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Message Details')->schema([
                    InfoGrid::make(3)->schema([
                        TextEntry::make('name')
                            ->weight('bold'),

                        TextEntry::make('email')
                            ->copyable()
                            ->icon('heroicon-m-envelope'),

                        TextEntry::make('created_at')
                            ->label('Received At')
                            ->dateTime('M j, Y — g:i A'),
                    ]),

                    TextEntry::make('subject')
                        ->weight('medium')
                        ->placeholder('No Subject'),

                    TextEntry::make('body')
                        ->label('Message Content')
                        ->columnSpanFull()
                        ->markdown(),

                    TextEntry::make('read_at')
                        ->label('Read Status')
                        ->badge()
                        ->formatStateUsing(fn ($state) => $state ? 'Read on ' . $state->format('M j, Y') : 'Unread')
                        ->color(fn ($state) => $state ? 'gray' : 'warning'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(fn (Message $record): string => is_null($record->read_at) ? 'bold' : 'normal'),

                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->color('gray'),

                TextColumn::make('subject')
                    ->searchable()
                    ->limit(40)
                    ->weight(fn (Message $record): string => is_null($record->read_at) ? 'bold' : 'normal')
                    ->placeholder('(No subject)'),

                TextColumn::make('read_at')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_null($state) ? 'Unread' : 'Read')
                    ->color(fn ($state) => is_null($state) ? 'warning' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('read_at')
                    ->label('Read Status')
                    ->nullable()
                    ->trueLabel('Read')
                    ->falseLabel('Unread'),
            ])
            ->actions([
                ViewAction::make(),

                Action::make('markAsRead')
                    ->label('Mark Read')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Message $record): bool => is_null($record->read_at))
                    ->action(fn (Message $record) => $record->markAsRead()),

                Action::make('markAsUnread')
                    ->label('Mark Unread')
                    ->icon('heroicon-o-envelope')
                    ->color('gray')
                    ->visible(fn (Message $record): bool => !is_null($record->read_at))
                    ->action(fn (Message $record) => $record->update(['read_at' => null])),

                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('markAsRead')
                        ->label('Mark Selected as Read')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Collection $records) => $records->each->markAsRead()),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessages::route('/'),
            'view' => Pages\ViewMessage::route('/{record}'),
        ];
    }
}
