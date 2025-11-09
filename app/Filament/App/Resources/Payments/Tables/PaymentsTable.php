<?php

namespace App\Filament\App\Resources\Payments\Tables;

use App\Filament\Shared\Columns\CreatedAtColumn;
use App\Filament\Shared\Columns\UpdatedAtColumn;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable(),

                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable(),

                TextColumn::make('invoice.code')
                    ->searchable()
                    ->label('Invoice'),

                TextColumn::make('user.full_name')
                    ->label('Created by')
                    ->sortable(),

                TextColumn::make('payment_method_id')
                    ->label('Payment method')
                    ->sortable(),

                TextColumn::make('client.full_name')
                    ->searchable()
                    ->label('Client'),

                TextColumn::make('payment_at')
                    ->dateTime()
                    ->label('Payment date')
                    ->sortable(),

                TextColumn::make('amount')
                    ->summarize(Sum::make()->money('EUR', 100)->label('Total amount'))
                    ->label('Amount')
                    ->numeric(2)
                    ->sortable()
                    ->suffix(' EUR')
                    ->color(function ($record) {
                        return $record->storno_of_id ? 'danger' : 'success';
                    })
                    ->weight(FontWeight::Bold)
                    ->sortable(),

                CreatedAtColumn::make('created_at'),
                UpdatedAtColumn::make('updated_at'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
