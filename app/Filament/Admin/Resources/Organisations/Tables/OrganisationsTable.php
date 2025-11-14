<?php

namespace App\Filament\Admin\Resources\Organisations\Tables;

use App\Models\Organisation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OrganisationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->width('50px')
                    ->circular()
                    ->label(''),

                TextColumn::make('name')
                    ->weight(FontWeight::Bold)
                    ->description(function (Organisation $record) {
                        return "Created at: {$record->created_at->format('d.m.Y')}";
                    })
                    ->label('Name')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->icon(Heroicon::AtSymbol)
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->copyable()
                    ->icon(Heroicon::Phone)
                    ->searchable(),

                TextColumn::make('subdomain')
                    ->label('Subdomain')
                    ->searchable(),

                TextColumn::make('full_address')
                    ->label('Address')
                    ->searchable(['address', 'city', 'zip_code']),

                TextColumn::make('users_count')
                    ->alignEnd()
                    ->badge()
                    ->counts('users')
                    ->color('primary')
                    ->label('Total users'),


            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
