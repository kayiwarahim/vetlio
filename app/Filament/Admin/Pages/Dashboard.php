<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\OrganisationCreatedByMonth;
use App\Filament\Admin\Widgets\StatsOverview;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends Page
{
    protected string $view = 'filament.admin.pages.dashboard';

    protected ?string $heading = 'Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Home;

    public function getSubheading(): string|Htmlable|null
    {
        return 'Welcome back to admin dashboard. Check out these system statistics and notifications from our insight reports.';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
            OrganisationCreatedByMonth::class,
        ];
    }
}
