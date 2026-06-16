<?php

namespace App\Contracts\Recruitment;

use App\Models\Recruitment\Application;

interface CreatesApplications
{
    /**
     * Cria a candidatura, persiste o currículo e notifica o RH por e-mail.
     *
     * @param  array{name:string,email:string,phone:?string,area:?string,resume_path:string}  $payload
     */
    public function handle(array $payload): Application;
}
