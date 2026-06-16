<?php

if (! function_exists('generate_code')) {
    function generate_code(): string
    {
        $ms = (int) round(microtime(true) * 1000);

        return strtoupper(base_convert((string) $ms, 10, 36));
    }
}
