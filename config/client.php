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

    // Clientes exibidos no site (logo em public/assets/cirna/clientes).
    'clients' => [
        ['name' => 'Marcopolo', 'logo' => 'marcopolo.png', 'url' => 'https://www.marcopolo.com.br/'],
        ['name' => 'Agrale', 'logo' => 'agrale.png', 'url' => 'https://www.agrale.com.br/'],
        ['name' => 'Neobus', 'logo' => 'neobus.png', 'url' => 'https://www.neobus.com.br/'],
        ['name' => 'GKN Driveline', 'logo' => 'gkn.png', 'url' => 'https://www.gkndriveline.com/'],
        ['name' => 'Spheros', 'logo' => 'spheros.png', 'url' => 'https://www.spheros.com.br/'],
        ['name' => 'RGB do Brasil', 'logo' => 'rgb.png', 'url' => 'https://www.rgb.ind.br/'],
        ['name' => 'Espumatec', 'logo' => 'espumatec.png', 'url' => 'https://www.espumatec.com.br/'],
        ['name' => 'Danna', 'logo' => 'danna.png', 'url' => 'https://www.danna.com.br/'],
    ],
];
