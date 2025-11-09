<?php

namespace App\Services;

use App\Models\Reservation;
use App\Notifications\ReservationCanceled;

class ReservationService
{
    public function cancel(Reservation $reservation, $cancelReason, $sendEmail = false): void
    {
        if ($reservation->canceled_at) return;

        $reservation->update([
            'canceled_at' => now(),
            'cancel_reason' => $cancelReason,
        ]);

        //Alert service provider
        $reservation->serviceProvider->notify(new ReservationCanceled($reservation));

        //Send email to client
        if ($sendEmail) {
            $reservation->client->notify(new ReservationCanceled($reservation));
        }
    }
}
