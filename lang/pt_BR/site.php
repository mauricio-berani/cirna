<?php

return [
    'sectors' => [
        'general'    => 'Geral',
        'sales'      => 'Vendas',
        'purchasing' => 'Compras',
        'quality'    => 'Qualidade',
        'finance'    => 'Financeiro',
        'tooling'    => 'Ferramentaria',
    ],

    'contact' => [
        'fields' => [
            'name'    => 'Nome',
            'email'   => 'E-mail',
            'phone'   => 'Telefone',
            'sector'  => 'Área de interesse',
            'message' => 'Mensagem',
        ],
        'submit' => 'Enviar mensagem',
        'feedback' => [
            'success'  => 'Mensagem enviada com sucesso! Em breve entraremos em contato.',
            'error'    => 'Não foi possível enviar sua mensagem. Tente novamente mais tarde.',
            'throttle' => 'Muitas tentativas. Aguarde :seconds segundos antes de enviar novamente.',
        ],
        'email' => [
            'subject' => 'Novo contato pelo site — :sector',
            'heading' => 'Nova mensagem de contato',
            'reply'   => 'Responder ao cliente',
        ],
    ],

    'quality' => [
        'view_certificate' => 'Visualizar certificado ISO',
    ],

    'careers' => [
        'submit'      => 'Enviar candidatura',
        'resume_hint' => 'Apenas PDF, tamanho máximo de 5 MB.',
        'resume_pdf'  => 'Currículo em PDF anexado pelo candidato.',
        'uploading'   => 'Enviando arquivo...',
        'areas' => [
            'production'     => 'Produção',
            'tooling'        => 'Ferramentaria',
            'quality'        => 'Qualidade',
            'administrative' => 'Administrativo',
            'commercial'     => 'Comercial',
            'other'          => 'Outra área',
        ],
        'feedback' => [
            'success'     => 'Candidatura enviada com sucesso! Obrigado pelo seu interesse.',
            'error'       => 'Não foi possível enviar sua candidatura. Tente novamente mais tarde.',
            'throttle'    => 'Muitas tentativas. Aguarde :seconds segundos antes de enviar novamente.',
            'invalid_pdf' => 'O arquivo enviado não é um PDF válido.',
        ],
        'email' => [
            'subject'         => 'Nova candidatura — :name',
            'heading'         => 'Nova candidatura recebida pelo site',
            'reply'           => 'Responder ao candidato',
            'attachment_note' => 'O currículo do candidato está anexado a este e-mail em PDF.',
        ],
    ],
];
