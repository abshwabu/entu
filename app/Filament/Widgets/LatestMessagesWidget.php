<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\MessageResource;
use App\Models\Message;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestMessagesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Message::query()->latest()->limit(5)
            )
            ->heading('Recent Inquiries')
            ->columns([
                TextColumn::make('name')
                    ->weight(fn (Message $record): string => is_null($record->read_at) ? 'bold' : 'normal')
                    ->searchable(),

                TextColumn::make('email')
                    ->copyable()
                    ->color('gray'),

                TextColumn::make('subject')
                    ->limit(40)
                    ->placeholder('(No subject)'),

                TextColumn::make('read_at')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_null($state) ? 'Unread' : 'Read')
                    ->color(fn ($state) => is_null($state) ? 'warning' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->since(),
            ])
            ->actions([
                Action::make('view')
                    ->url(fn (Message $record): string => MessageResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye')
                    ->tooltip('View message details'),

                Action::make('markAsRead')
                    ->label('Mark Read')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Message $record): bool => is_null($record->read_at))
                    ->action(fn (Message $record) => $record->markAsRead()),
            ])
            ->paginated(false);
    }
}
