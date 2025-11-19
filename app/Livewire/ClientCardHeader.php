<?php

namespace App\Livewire;

use App\Enums\Icons\PhosphorIcons;
use App\Models\Client;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Livewire\Component;

class ClientCardHeader extends Component implements HasSchemas
{
    use InteractsWithSchemas, InteractsWithRecord;

    public function mount(): void
    {
        $this->record = Client::find(request('record'));
    }

    public function headerSchema(Schema $schema)
    {
        return $schema
            ->record($this->record)
            ->extraAttributes([
                'class' => 'mb-5'
            ])
            ->schema([
                Grid::make(1)
                    ->gap(false)
                    ->extraAttributes([
                        'class' => 'gap-y-4'
                    ])
                    ->schema([
                        ImageEntry::make('avatar_url')
                            ->hiddenLabel()
                            ->circular()
                            ->extraImgAttributes([
                                'class' => 'border-1 border-primary-500 p-1'
                            ])
                            ->extraEntryWrapperAttributes([
                                'class' => 'mb-2'
                            ])
                            ->alignCenter(),
                        TextEntry::make('full_name')
                            ->hiddenLabel()
                            ->size(TextSize::Large)
                            ->alignCenter()
                            ->weight(FontWeight::Bold),

                        TextEntry::make('full_address')
                            ->size(TextSize::Small)
                            ->color('gray')
                            ->hiddenLabel()
                            ->alignCenter(),

                        TextEntry::make('email')
                            ->size(TextSize::Small)
                            ->copyable()
                            ->icon(PhosphorIcons::EnvelopeOpen)
                            ->color('gray')
                            ->hiddenLabel()
                            ->alignCenter(),

                        TextEntry::make('phone')
                            ->size(TextSize::Small)
                            ->copyable()
                            ->icon(PhosphorIcons::Phone)
                            ->color('gray')
                            ->hiddenLabel()
                            ->alignCenter(),

                        SpatieTagsEntry::make('tags')
                            ->extraEntryWrapperAttributes([
                                'class' => 'mt-2'
                            ])
                            ->alignCenter()
                            ->hiddenLabel()
                    ])
            ]);
    }

    public function render()
    {
        return view('livewire.client-card-header');
    }
}
