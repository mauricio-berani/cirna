<?php

namespace App\Contracts\Common;

use App\Models\Common\Client;

interface CreatesClients
{
    public function handle(array $payload): Client;
}
