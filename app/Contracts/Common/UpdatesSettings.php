<?php

namespace App\Contracts\Common;

interface UpdatesSettings
{
    /**
     * Persiste as configurações gerais do sistema.
     *
     * @param  array<string, string|null>  $payload
     */
    public function handle(array $payload): void;
}
