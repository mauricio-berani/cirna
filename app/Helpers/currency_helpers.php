<?php

if (! function_exists('currency_to_cents')) {
    function currency_to_cents(?string $value): ?int
    {
        if (! $value) {
            return null;
        }

        $value = trim($value);
        $value = str_replace('.', '', $value);

        if (strpos($value, ',') === false) {
            $value .= ',00';
        }

        [$integerPart, $decimalPart] = explode(',', $value);
        $decimalPart = str_pad(substr($decimalPart, 0, 2), 2, '0', STR_PAD_RIGHT);

        return (int) ($integerPart.$decimalPart);
    }
}

if (! function_exists('cents_to_currency')) {
    function cents_to_currency(?int $cents): ?string
    {
        if (! $cents) {
            return null;
        }

        return number_format($cents / 100, 2, ',', '.');
    }
}
