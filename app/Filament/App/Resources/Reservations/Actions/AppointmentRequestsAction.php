<?php

namespace App\Filament\App\Resources\Reservations\Actions;

use App\Enums\Icons\PhosphorIcons;
use App\Filament\App\Pages\AppointmentRequests;
use App\Services\ReservationService;
use CodeWithDennis\SimpleAlert\Components\SimpleAlert;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class AppointmentRequestsAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->modalIcon(PhosphorIcons::UserPlus);
        $this->modalDescription(function ($livewire) {
            return 'You have ' . count($livewire->appointmentRequests) . ' appointment requests';
        });
        $this->url(function ($livewire) {
            return count($livewire->appointmentRequests) < 1 ? AppointmentRequests::getUrl() : null;
        });
        $this->hiddenLabel();
        $this->tooltip('Appointment requests');
        $this->badge(function ($livewire) {
            return count($livewire->appointmentRequests);
        });
        $this->modalSubmitAction(false);
        $this->button();
        $this->slideOver();
        $this->record(Filament::getTenant());
        $this->schema([
            Action::make('view-all')
                ->url(AppointmentRequests::getUrl())
                ->icon(PhosphorIcons::CalendarPlus)
                ->link()
                ->label('View all request')
                ->extraAttributes(['class' => 'text-right']),
            SimpleAlert::make('no-requests')
                ->success()
                ->border()
                ->visible(fn($livewire) => count($livewire->appointmentRequests) < 1)
                ->icon(PhosphorIcons::CheckCircleBold)
                ->columnSpanFull()
                ->title('No requests available'),
            RepeatableEntry::make('appointmentRequests')
                ->hiddenLabel()
                ->contained(false)
                ->getStateUsing(function ($livewire) {
                    return $livewire->appointmentRequests;
                })
                ->visible(fn($livewire) => $livewire->appointmentRequests)
                ->schema([
                    Section::make(function ($record) {
                        return new HtmlString("<small>Request from {$record->client->full_name}</small>");
                    })
                        ->compact()
                        ->icon(PhosphorIcons::UserPlus)
                        ->columns(3)
                        ->headerActions([
                            Action::make('approve')
                                ->label('Approve')
                                ->link()
                                ->requiresConfirmation()
                                ->icon(PhosphorIcons::CheckCircleBold)
                                ->successNotificationTitle('The appointment request has been approved')
                                ->action(function ($record, $component, Component $livewire, $get) {
                                    app(ReservationService::class)->approveRequest($record);
                                    $livewire->appointmentRequests = $livewire->appointmentRequests->reject(fn($r) => $r->id === $record->id);
                                }),
                            Action::make('deny')
                                ->label('Deny')
                                ->link()
                                ->color('danger')
                                ->icon(PhosphorIcons::XCircleBold)
                                ->successNotificationTitle('The appointment request has been denied')
                                ->action(function ($record, $livewire) {
                                    app(ReservationService::class)->denyRequest($record);

                                    $livewire->appointmentRequests = $livewire->appointmentRequests->reject(fn($r) => $r->id === $record->id);
                                })
                        ])
                        ->schema([
                            TextEntry::make('client.full_name')
                                ->label('Client'),
                            TextEntry::make('client.email')
                                ->label('Email'),
                            TextEntry::make('client.phone')
                                ->label('Phone')
                                ->default('-'),
                            TextEntry::make('patient.name')
                                ->label('Patient'),
                            TextEntry::make('from')
                                ->label('Date')
                                ->dateTime(),
                            TextEntry::make('service.name')
                                ->label('Service'),

                        ])
                ])
        ]);
        $this->badgeColor('success');
        $this->icon(PhosphorIcons::UserPlus);
    }

    public static function getDefaultName(): ?string
    {
        return 'appointment-requests-action';
    }
}
