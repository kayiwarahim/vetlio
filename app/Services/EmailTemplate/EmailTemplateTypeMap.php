<?php

namespace App\Services\EmailTemplate;

use App\Enums\EmailTemplateType;
use App\Services\EmailTemplate\Tags\AppointmentTags;
use App\Services\EmailTemplate\Tags\BranchTags;
use App\Services\EmailTemplate\Tags\ClientTags;
use App\Services\EmailTemplate\Tags\OrganisationTags;

class EmailTemplateTypeMap
{
    public static function getProvidersForType(int $typeId): array
    {
        return match ($typeId) {
            EmailTemplateType::CancelAppointment->value, EmailTemplateType::NewAppointment->value => [
                OrganisationTags::class,
                ClientTags::class,
                BranchTags::class,
                AppointmentTags::class
            ],
            default => [],
        };
    }
}
