<?php

namespace App\Filament\App\Resources\Reservations\Actions;

use App\Filament\App\Resources\Reservations\Schemas\ReservationInfolist;
use Filament\Actions\ViewAction;

class ViewAppointmentAction extends ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->schema(function ($schema) {
            return ReservationInfolist::configure($schema);
        });
    }
}
