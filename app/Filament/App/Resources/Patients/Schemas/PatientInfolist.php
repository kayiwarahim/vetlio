<?php

namespace App\Filament\App\Resources\Patients\Schemas;

use App\Enums\Icons\PhosphorIcons;
use CodeWithDennis\SimpleAlert\Components\SimpleAlert;
use Filament\Actions\Action;
use Filament\Schemas\Schema;

class PatientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SimpleAlert::make('archived-info')
                    ->warning()
                    ->border()
                    ->actions([
                        Action::make('unarchive-patient')
                            ->label('Unarchive')
                            ->icon(PhosphorIcons::FilePlus)
                            ->link()
                            ->modalIcon(PhosphorIcons::FilePlus)
                            ->modalHeading('Unarchive patient')
                            ->modalSubmitActionLabel('Unarchive')
                            ->successNotificationTitle('The patient has been successfully unarchived')
                            ->requiresConfirmation()
                            ->action(function ($record) {
                                $record->update([
                                    'archived_at' => null,
                                    'archived_note' => null,
                                    'archived_by' => null,
                                ]);
                            }),
                    ])
                    ->columnSpanFull()
                    ->visible(fn($record) => $record->archived_at)
                    ->title('Patient is archived')
                    ->description(function ($record) {
                        $archivedNote = $record->archived_note
                            ? 'Reason for archiving: ' . $record->archived_note
                            : 'No reason provided';
                        return $archivedNote;
                    }),

                SimpleAlert::make('dangerous-info')
                    ->danger()
                    ->border()
                    ->columnSpanFull()
                    ->visible(fn($record) => $record->dangerous && $record->archived_at == null)
                    ->title('Patient marked as dangerous')
                    ->description(function ($record) {
                        return $record->dangerous_note
                            ? 'Note: ' . $record->dangerous_note
                            : null;
                    }),
            ]);
    }
}
