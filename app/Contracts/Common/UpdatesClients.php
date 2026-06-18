<?php

namespace App\Contracts\Common;

use App\Models\Common\Client;

interface UpdatesClients
{
    public function handle(Client $client, array $payload): Client;
}
