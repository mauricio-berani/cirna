<?php

namespace App\Enums\Recruitment;

enum ApplicationArea: string
{
    case PRODUCTION = 'production';
    case TOOLING = 'tooling';
    case QUALITY = 'quality';
    case ADMINISTRATIVE = 'administrative';
    case COMMERCIAL = 'commercial';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::PRODUCTION => __('site.careers.areas.production'),
            self::TOOLING => __('site.careers.areas.tooling'),
            self::QUALITY => __('site.careers.areas.quality'),
            self::ADMINISTRATIVE => __('site.careers.areas.administrative'),
            self::COMMERCIAL => __('site.careers.areas.commercial'),
            self::OTHER => __('site.careers.areas.other'),
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
