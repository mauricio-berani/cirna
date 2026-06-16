<?php

namespace App\Contracts\Site;

interface SendsContactMessages
{
    /**
     * Envia a mensagem de contato do site institucional para a empresa.
     *
     * @param  array{name:string,email:string,phone:?string,sector:?string,message:string,sector_label:string}  $payload
     */
    public function handle(array $payload): void;
}
