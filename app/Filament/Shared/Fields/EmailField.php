<?php

namespace App\Filament\Shared\Fields;

use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

class EmailField extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Email');
        $this->prefixIcon(Heroicon::AtSymbol);
        $this->required();
        $this->email();
    }
}
