<?php

namespace App\Services\EmailTemplate;

use App\Enums\EmailTemplateType;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor\RichContentRenderer;

class EmailTemplateRenderer
{
    protected int|EmailTemplateType $type;
    protected array $context = [];
    protected string $language = 'en';
    protected ?int $branchId = null;

    public static function make(): static
    {
        return new static();
    }

    public function for(int|EmailTemplateType $type): static
    {
        $this->type = $type instanceof EmailTemplateType ? $type->value : $type;
        return $this;
    }

    public function withContext(array $context): static
    {
        $this->context = $context;
        return $this;
    }

    public function forLanguage(string $lang): static
    {
        $this->language = $lang;
        return $this;
    }

    public function forBranch(?int $branchId): static
    {
        $this->branchId = $branchId;
        return $this;
    }

    public function resolve(): ?array
    {
        $branchId = $this->branchId ?? Filament::getTenant()?->id;

        if (!$branchId) {
            return null;
        }

        $template = app(EmailTemplateService::class)
            ->getTemplateContent($branchId, $this->type, $this->language);

        if (!$template) {
            return null;
        }

        $resolver = (new MergeTagResolver())
            ->forEmailTemplate($this->type)
            ->context($this->context);

        $tags = $resolver->resolve();

        $subject = $this->replaceTags($template->subject, $tags);

        $body = RichContentRenderer::make($template->content)
            ->mergeTags($tags)
            ->toHtml();

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }

    protected function replaceTags(?string $content, array $tags): ?string
    {
        if (!$content) {
            return $content;
        }

        foreach ($tags as $tag => $value) {
            $content = str_replace('{{' . $tag . '}}', $value, $content);
        }

        return $content;
    }
}
