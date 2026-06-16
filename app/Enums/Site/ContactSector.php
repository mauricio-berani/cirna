<?php

namespace App\Enums\Site;

enum ContactSector: string
{
    case GENERAL = 'general';
    case SALES = 'sales';
    case PURCHASING = 'purchasing';
    case QUALITY = 'quality';
    case FINANCE = 'finance';
    case TOOLING = 'tooling';

    public function label(): string
    {
        return match ($this) {
            self::GENERAL => __('site.sectors.general'),
            self::SALES => __('site.sectors.sales'),
            self::PURCHASING => __('site.sectors.purchasing'),
            self::QUALITY => __('site.sectors.quality'),
            self::FINANCE => __('site.sectors.finance'),
            self::TOOLING => __('site.sectors.tooling'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
        ];
    }

    public static function options(): array
    {
        return array_merge(
            [['value' => null, 'label' => __('interface.select.option')]],
            array_map(fn (self $item) => $item->toArray(), self::cases())
        );
    }
}
