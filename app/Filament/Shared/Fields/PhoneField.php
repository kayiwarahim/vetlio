<?php

namespace App\Filament\Shared\Fields;

use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

class PhoneField extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Phone');
        $this->tel();
        $this->prefixIcon(Heroicon::Phone);
    }
}
