<?php

namespace App\Filament\App\Resources\Tasks\Tables;

use App\Enums\TaskStatus;
use App\Filament\Columns\PriorityColumn;
use App\Filament\Shared\Columns\CreatedAtColumn;
use App\Filament\Shared\Columns\UpdatedAtColumn;
use App\Models\Task;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->with(['related']))
            ->columns([
                TextColumn::make('title')
                    ->icon(fn(Task $record) => $record->hasMedia() ? Heroicon::PaperClip : null)
                    ->label('Title')
                    ->description(function (Task $record) {
                        return $record->related->relatedLabel() . ': ' . $record->related->relatedValue();
                    })
                    ->weight(FontWeight::Medium)
                    ->searchable(),

                TextColumn::make('user.full_name')
                    ->label('Created By')
                    ->searchable(),

                TextColumn::make('related.name')
                    ->hidden()
                    ->label('Related To')
                    ->searchable(),

                TextColumn::make('status_id')
                    ->formatStateUsing(fn($state) => TaskStatus::from($state)->getLabel())
                    ->badge()
                    ->label('Status')
                    ->sortable(),

                TextColumn::make('assignedUsers.full_name')
                    ->limitList()
                    ->label('Assigned To'),

                TextColumn::make('start_at')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('deadline_at')
                    ->label('Deadline')
                    ->description(fn(Task $record) => $record?->deadline_at?->diffForHumans())
                    ->date()
                    ->sortable(),

                SpatieTagsColumn::make('tags')
                    ->label('Tags'),

                CreatedAtColumn::make('created_at'),
                UpdatedAtColumn::make('updated_at'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
