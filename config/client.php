<?php

return [
    // Nome curto exibido no <title> e na marca.
    'name' => env('CLIENT_NAME', 'Cirna'),

    // Razão social completa (rodapé, página de contato).
    'legal_name' => 'Cirna Indústria de Plásticos e Moldes LTDA',

    // Ano de fundação — usado para calcular "X anos de mercado".
    'founded_year' => 1972,

    // Contato / endereço.
    'address' => 'Rua Padre Raul Accorsi, 900 — Bairro de Zorzi',
    'city' => 'Caxias do Sul',
    'state' => 'RS',
    'zip' => '95074-300',
    'phone' => '(54) 3212-1644',
    'phone_e164' => '+555432121644',
    'email' => env('CIRNA_PUBLIC_EMAIL', 'contato@cirna.com.br'),

    // Número de WhatsApp em formato internacional só com dígitos (ex.: 5554999999999).
    // Deixe vazio para ocultar o botão de WhatsApp do site.
    'whatsapp' => env('CIRNA_WHATSAPP', ''),

    // Destino do formulário de contato.
    'contact_email' => env('CIRNA_CONTACT_EMAIL', 'contato@cirna.com.br'),

    // Mapa (Google Maps embed por endereço, sem necessidade de API key).
    'maps_query' => 'Cirna Industria de Plasticos e Moldes, Rua Padre Raul Accorsi 900, Caxias do Sul RS',

    // Certificação exibida no site.
    'certification' => 'ISO 9001:2015',

    // Os clientes exibidos no site agora são gerenciados pelo painel (módulo Clientes)
    // e persistidos na tabela `clients`. Veja App\Models\Common\Client e ClientSeeder.
];
