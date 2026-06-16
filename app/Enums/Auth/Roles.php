<?php

namespace App\Enums\Auth;

enum Roles: string
{
    case USER = 'user';
    case MANAGER = 'manager';
    case ADMINISTRATOR = 'administrator';

    public function label(): string
    {
        return match ($this) {
            self::USER => __('enums.auth.roles.user'),
            self::MANAGER => __('enums.auth.roles.manager'),
            self::ADMINISTRATOR => __('enums.auth.roles.administrator'),
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
