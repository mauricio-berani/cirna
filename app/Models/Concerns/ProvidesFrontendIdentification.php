<?php

namespace App\Models\Concerns;

trait ProvidesFrontendIdentification
{
    protected static function getTextPath(): string
    {
        return defined(sprintf('%s::CUSTOM_PATH', static::class))
            ? constant(sprintf('%s::CUSTOM_PATH', static::class))
            : (new static)->getTable();
    }

    public static function getFrontendTitle(?string $page = null): string
    {
        $path = static::getTextPath();

        return $page
            ? __(sprintf('interface.identification.%s.%s.title', $path, $page))
            : __(sprintf('interface.identification.%s.title', $path));
    }

    public static function getFrontendSubtitle(?string $page = null): string
    {
        $path = static::getTextPath();

        return $page
            ? __(sprintf('interface.identification.%s.%s.subtitle', $path, $page))
            : __(sprintf('interface.identification.%s.subtitle', $path));
    }
}
