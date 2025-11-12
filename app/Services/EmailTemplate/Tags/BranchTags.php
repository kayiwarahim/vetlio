<?php

namespace App\Services\EmailTemplate\Tags;

use App\Contracts\EmailTagProvider;
use App\Models\Branch;

class BranchTags implements EmailTagProvider
{
    public function supports(mixed $model): bool
    {
        return $model instanceof Branch;
    }

    public static function getAvailableTags(): array
    {
        return [
            'branch.id' => 'Branch ID',
            'branch.name' => 'Branch Name',
        ];
    }

    public function resolve(mixed $model): array
    {
        return [
            'branch.id' => $model->id,
            'branch.name' => $model->name,
        ];
    }
}
