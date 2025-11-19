<?php

namespace App\Filament\App\Resources\Reservations\Actions;

use App\Enums\Icons\PhosphorIcons;
use App\Filament\App\Resources\Reservations\Schemas\ReservationForm;
use App\Models\Reservation;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Support\Enums\Width;

class NewAppointmentAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('New appointment');
        $this->modalWidth(Width::SixExtraLarge);
        $this->modalHeading('New appointment');
        $this->modalDescription('Create a new appointment');
        $this->slideOver();
        $this->icon(PhosphorIcons::CalendarPlus);
        $this->modalSubmitActionLabel('Create appointment');
        $this->modalIcon(PhosphorIcons::CalendarPlus);
        $this->model(Reservation::class);
        $this->schema(function ($schema) {
            return ReservationForm::configure($schema)
                ->columns(2);
        });
        $this->color('success');
        $this->after(function($livewire) {
            //emit event...
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'create-appointment-action';
    }
}
