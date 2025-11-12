<?php

namespace App\Services\EmailTemplate;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateContent;
use Illuminate\Support\Facades\Cache;

class EmailTemplateService
{
    public function getTemplateContent($branchId, int $actionId, string $lang = 'en'): ?EmailTemplateContent
    {
        $cacheKey = "email_template.{$branchId}.{$actionId}";

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($actionId, $lang, $branchId) {
            $template = EmailTemplate::with('emailTemplateContents')
                ->where('type_id', $actionId)
                ->where('branch_id', $branchId)
                ->whereActive(true)
                ->first();

            if (!$template) {
                return null;
            }

            $content = $template->emailTemplateContents->firstWhere('language', $lang);

            if (!$content) {
                $content = $template->emailTemplateContents->firstWhere('language', 'en');
            }

            if (!$content) {
                $content = $template->emailTemplateContents->first();
            }

            return $content;
        });
    }

    public function clearTemplateCache(int $branchId, int $actionId): void
    {
        if ($branchId && $actionId) {
            Cache::forget("email_template.{$branchId}.{$actionId}");
        }
    }

}
