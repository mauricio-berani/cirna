<?php

namespace App\Services\Common;

use App\Contracts\Common\CreatesClients;
use App\Models\Common\Client;
use Illuminate\Support\Facades\DB;

class CreateClientService implements CreatesClients
{
    public function handle(array $payload): Client
    {
        return DB::transaction(function () use ($payload) {
            $client = new Client;

            $client->forceFill([
                Client::FIELD_NAME => $payload[Client::FIELD_NAME],
                Client::FIELD_LOGO => $payload[Client::FIELD_LOGO],
                Client::FIELD_URL => $payload[Client::FIELD_URL] ?? null,
            ])->save();

            return $client;
        });
    }
}
