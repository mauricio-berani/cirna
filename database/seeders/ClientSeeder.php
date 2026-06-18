<?php

namespace Database\Seeders;

use App\Models\Common\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Clientes exibidos no site institucional.
     *
     * Os logos estáticos vivem em public/assets/cirna/clientes.
     * Removidos a pedido: Danna, GKN, RGB e NeoBus.
     */
    public function run(): void
    {
        $clients = [
            ['name' => 'Marcopolo', 'logo' => 'assets/cirna/clientes/marcopolo.png', 'url' => 'https://www.marcopolo.com.br/'],
            ['name' => 'Agrale', 'logo' => 'assets/cirna/clientes/agrale.png', 'url' => 'https://www.agrale.com.br/'],
            ['name' => 'Spheros', 'logo' => 'assets/cirna/clientes/spheros.png', 'url' => 'https://www.spheros.com.br/'],
            ['name' => 'Espumatec', 'logo' => 'assets/cirna/clientes/espumatec.png', 'url' => 'https://www.espumatec.com.br/'],
        ];

        foreach ($clients as $client) {
            $existing = Client::query()
                ->where(Client::FIELD_NAME, $client['name'])
                ->first() ?? new Client;

            $existing->forceFill([
                Client::FIELD_NAME => $client['name'],
                Client::FIELD_LOGO => $client['logo'],
                Client::FIELD_URL => $client['url'],
            ])->save();
        }
    }
}
