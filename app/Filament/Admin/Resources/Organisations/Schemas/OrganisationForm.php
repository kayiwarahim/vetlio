<?php

namespace App\Filament\Admin\Resources\Organisations\Schemas;

use App\Filament\Shared\Fields\EmailField;
use App\Filament\Shared\Fields\PhoneField;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class OrganisationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),

                TextInput::make('subdomain')
                    ->required()
                    ->live(true)
                    ->unique('organisations', 'subdomain')
                    ->label('Subdomain'),

                TextInput::make('address')
                    ->required()
                    ->label('Address'),

                TextInput::make('city')
                    ->required()
                    ->label('City'),

                TextInput::make('zip_code')
                    ->required()
                    ->label('ZIP'),

                PhoneField::make('phone')
                    ->tel(),

                EmailField::make('email')
                    ->unique('organisations', 'email'),

                Fieldset::make('Administrator')
                    ->visibleOn('create')
                    ->columns(2)
                    ->columnSpanFull()
                    ->statePath('users')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('First name')
                            ->required(),

                        TextInput::make('last_name')
                            ->label('Last name')
                            ->required(),

                        EmailField::make('email')
                            ->columnSpanFull(),

                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->label('Password'),

                        TextInput::make('password_confirmation')
                            ->password()
                            ->required()
                            ->label('Confirm password'),
                    ])
            ]);
    }
}
