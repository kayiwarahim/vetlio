<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Resources\Reservations\Actions\AppointmentRequestsAction;
use App\Filament\App\Resources\Reservations\Actions\NewAppointmentAction;
use App\Filament\App\Widgets\CalendarWidget;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;

class Calendar extends Page
{
    protected string $view = 'filament.app.pages.calendar';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 55;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Calendar;

    protected static ?string $title = 'Calendar';

    protected Width|string|null $maxContentWidth = 'full';

    public ?Collection $appointmentRequests = null;

    public function getSubheading(): string|Htmlable|null
    {
        return Filament::getTenant()->name;
    }

    public function mount(): void
    {
        $this->appointmentRequests = Filament::getTenant()->appointmentRequests()->where('approval_at', null)->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            AppointmentRequestsAction::make(),
            NewAppointmentAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::make()
        ];
    }
}
