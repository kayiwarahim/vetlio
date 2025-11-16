<?php

namespace App\Filament\App\Resources\Reservations\Actions;

use Filament\Actions\EditAction;

class EditAppointmentAction extends EditAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->disabled(function ($record) {
            return $record->is_canceled || !$record->status_id->isOrdered();
        });

    }
}
