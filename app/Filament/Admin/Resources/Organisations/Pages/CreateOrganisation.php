<?php

namespace App\Filament\Admin\Resources\Organisations\Pages;

use App\Filament\Admin\Resources\Organisations\OrganisationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrganisation extends CreateRecord
{
    protected static string $resource = OrganisationResource::class;
}
