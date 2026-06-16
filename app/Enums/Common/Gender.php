<?php

namespace App\Enums\Common;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';

    public function label(): string
    {
        return match ($this) {
            self::Male => __('enums.common.gender.male'),
            self::Female => __('enums.common.gender.female'),
        };
    }

    public function toArray(): array
    {
        return [
            'label' => $this->label(),
            'value' => $this->value,
        ];
    }

    public static function options(): array
    {
        return array_merge(
            [['value' => null, 'label' => __('interface.select.option')]],
            array_map(fn ($item) => $item->toArray(), self::cases())
        );
    }
}
