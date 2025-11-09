<?php

namespace App\Filament\App\Resources\Tasks\Schemas;

use App\Enums\Priority;
use App\Filament\Fields\PriorityField;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\MedicalDocument;
use App\Models\Offer;
use App\Models\Patient;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->columnSpanFull()
                    ->label('Title')
                    ->required(),

                Grid::make(2)
                    ->columnSpan(1)
                    ->schema([
                        DatePicker::make('start_at')
                            ->required()
                            ->default(now())
                            ->label('Start Date'),

                        DatePicker::make('deadline_at')
                            ->after('start_at')
                            ->label('Deadline'),
                    ]),

                Select::make('priority_id')
                    ->label('Priority')
                    ->default(1)
                    ->options(Priority::class)
                    ->required(),

                Select::make('assignedUsers')
                    ->relationship('assignedUsers', 'first_name')
                    ->options(User::get()->pluck('full_name', 'id'))
                    ->label('Assigned To')
                    ->multiple(),

                SpatieTagsInput::make('tags')
                    ->label('Tags'),

                MorphToSelect::make('related')
                    ->contained(false)
                    ->label('Related To')
                    ->required()
                    ->native(false)
                    ->columnSpanFull()
                    ->columns(2)
                    ->extraAttributes([
                        'class' => 'morph-related-select',
                    ])
                    ->types([
                        MorphToSelect\Type::make(Client::class)
                            ->label('Client')
                            ->searchColumns(['first_name'])
                            ->titleAttribute('first_name'),

                        MorphToSelect\Type::make(Patient::class)
                            ->label('Patient')
                            ->searchColumns(['name'])
                            ->titleAttribute('name'),

                        MorphToSelect\Type::make(Invoice::class)
                            ->label('Invoice')
                            ->searchColumns(['code'])
                            ->titleAttribute('code'),

                        MorphToSelect\Type::make(MedicalDocument::class)
                            ->label('Medical Record')
                            ->searchColumns(['code'])
                            ->titleAttribute('code'),
                    ])
                    ->modifyKeySelectUsing(function (Select $select) {
                        $select->searchable(true);
                    }),

                RichEditor::make('description')
                    ->extraAttributes([
                        'style' => 'min-height: 200px',
                    ])
                    ->label('Description')
                    ->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('attachments')
                    ->label('Attachments')
                    ->columnSpanFull()
                    ->multiple(),
            ]);
    }
}
