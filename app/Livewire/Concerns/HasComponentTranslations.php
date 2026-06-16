<?php

namespace App\Livewire\Concerns;

trait HasComponentTranslations
{
    public function getTranslationsPath(): ?string
    {
        return null;
    }

    public function mountTranslationPath(?string $page = null): ?string
    {
        $path = $this->getTranslationsPath();

        if (! $path) {
            return null;
        }

        return $page
            ? __(sprintf('interface.identification.%s.%s.title', $path, $page))
            : __(sprintf('interface.identification.%s.title', $path));
    }
}
