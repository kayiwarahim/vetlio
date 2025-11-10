<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\Icons\PhosphorIcons;
use App\Models\Organisation;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected int|array|null $columns = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Total clinics', Organisation::count())
                ->description('Total number of clinics')
                ->icon(PhosphorIcons::Building)
                ->color('info'),

            Stat::make('Total users', User::count())
                ->description('Total number of users')
                ->icon(PhosphorIcons::Users)
                ->color('success'),

        ];
    }
}
