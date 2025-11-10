<?php

namespace App\Filament\Admin\Resources\Organisations;

use App\Filament\Admin\Resources\Organisations\Pages\CreateOrganisation;
use App\Filament\Admin\Resources\Organisations\Pages\EditOrganisation;
use App\Filament\Admin\Resources\Organisations\Pages\ListOrganisations;
use App\Filament\Admin\Resources\Organisations\Pages\ViewOrganisation;
use App\Filament\Admin\Resources\Organisations\Schemas\OrganisationForm;
use App\Filament\Admin\Resources\Organisations\Schemas\OrganisationInfolist;
use App\Filament\Admin\Resources\Organisations\Tables\OrganisationsTable;
use App\Models\Organisation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganisationResource extends Resource
{
    protected static ?string $model = Organisation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $label = 'organisation';

    protected static ?string $pluralLabel = 'organisations';

    protected static ?string $navigationLabel = 'Organisations';

    public static function form(Schema $schema): Schema
    {
        return OrganisationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrganisationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganisationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrganisations::route('/'),
            //'create' => CreateOrganisation::route('/create'),
            'view' => ViewOrganisation::route('/{record}'),
            //'edit' => EditOrganisation::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
