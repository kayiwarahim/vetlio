<?php

namespace App\Filament\Portal\Resources\Patients\Pages;

use App\Enums\Icons\PhosphorIcons;
use App\Filament\App\Actions\ClientCardAction;
use App\Filament\Portal\Resources\Patients\PatientResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;

    public function getSubheading(): string|Htmlable|null
    {
        return $this->getRecord()->description;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('unarchive')
                ->visible(fn() => $this->getRecord()->archived_at != null)
                ->label('Unarchive')
                ->icon(PhosphorIcons::FilePlus)
                ->color('success')
                ->modalIcon(PhosphorIcons::FilePlus)
                ->modalHeading('Unarchive patient')
                ->modalSubmitActionLabel('Unarchive')
                ->successNotificationTitle('The patient has been successfully unarchived')
                ->requiresConfirmation()
                ->action(function ($data) {
                    $this->getRecord()->update([
                        'archived_at' => null,
                        'archived_note' => null,
                        'archived_by' => null,
                    ]);
                }),

            Action::make('archive')
                ->visible(fn() => $this->getRecord()->archived_at === null)
                ->label('Archive')
                ->icon(PhosphorIcons::FileMinus)
                ->color('danger')
                ->modalIcon(PhosphorIcons::FileMinus)
                ->modalHeading('Archive patient')
                ->modalSubmitActionLabel('Archive')
                ->schema([
                    Textarea::make('archived_note')
                        ->rows(3)
                        ->label('Reason for archiving'),
                ])
                ->successNotificationTitle('The patient has been archived')
                ->requiresConfirmation()
                ->action(function ($data) {
                    $this->getRecord()->update([
                        'archived_at' => now(),
                        'archived_note' => (string) $data['archived_note'],
                        'archived_by' => auth()->user()->id,
                    ]);
                }),

            EditAction::make(),
        ];
    }
}
