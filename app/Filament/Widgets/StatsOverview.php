<?php

namespace App\Filament\Widgets;

use App\Models\Message;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $unreadCount = Message::unread()->count();

        return [
            Stat::make('Total Projects', Project::count())
                ->description(Project::published()->count() . ' published live')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart([7, 8, 10, 11, 12]),

            Stat::make('Unread Messages', $unreadCount)
                ->description($unreadCount > 0 ? 'Requires attention' : 'All caught up')
                ->descriptionIcon($unreadCount > 0 ? 'heroicon-m-envelope' : 'heroicon-m-check-badge')
                ->color($unreadCount > 0 ? 'warning' : 'success'),

            Stat::make('Featured Projects', Project::featured()->count())
                ->description('Showcased on homepage')
                ->descriptionIcon('heroicon-m-star')
                ->color('amber'),
        ];
    }
}
