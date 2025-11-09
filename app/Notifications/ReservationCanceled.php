<?php

namespace App\Notifications;

use App\Enums\Icons\PhosphorIcons;
use App\Filament\App\Resources\Tickets\TicketResource;
use App\Models\Reservation;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCanceled extends Notification
{
    use Queueable;

    private Reservation $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting("Hi {$notifiable->full_name},")
            ->line("Your reservation scheduled at {$this->reservation->from->format('H:i')} has been canceled.")
            ->lineIf($this->reservation->cancel_reason, "Reason: {$this->reservation->cancel_reason}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if (!$notifiable instanceof User) return [];

        return FilamentNotification::make()
            ->title('Reservation has been canceled')
            ->icon(PhosphorIcons::CalendarX)
            ->body(function () {
                $scheduleAt = $this->reservation->from->format('H:i');
                $reason = $this->reservation->cancel_reason;
                $client = $this->reservation->client->full_name;

                return "Reservation scheduled at {$scheduleAt} for {$client} has been canceled. Reason: {$reason}.";
            })
            ->getDatabaseMessage();
    }
}
