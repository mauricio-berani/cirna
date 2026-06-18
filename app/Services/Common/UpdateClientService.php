<?php

namespace App\Services\Common;

use App\Contracts\Common\UpdatesClients;
use App\Models\Common\Client;
use Illuminate\Support\Facades\DB;

class UpdateClientService implements UpdatesClients
{
    public function handle(Client $client, array $payload): Client
    {
        return DB::transaction(function () use ($client, $payload) {
            $data = [
                Client::FIELD_NAME => $payload[Client::FIELD_NAME],
                Client::FIELD_URL => $payload[Client::FIELD_URL] ?? null,
            ];

            if (! empty($payload[Client::FIELD_LOGO])) {
                $data[Client::FIELD_LOGO] = $payload[Client::FIELD_LOGO];
            }

            $client->forceFill($data)->save();

            return $client;
        });
    }
}
