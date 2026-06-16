<?php

namespace App\Services\Site;

use App\Contracts\Site\SendsContactMessages;
use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Mail;

class SendContactMessageService implements SendsContactMessages
{
    /**
     * @param  array{name:string,email:string,phone:?string,sector:?string,message:string,sector_label:string}  $payload
     */
    public function handle(array $payload): void
    {
        Mail::to(config('client.contact_email'))
            ->send(new ContactMessageMail($payload));
    }
}
