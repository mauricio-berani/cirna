<?php

namespace App\Livewire\Forms\Auth;

use App\Models\Auth\User;
use Livewire\Form;

class LoginForm extends Form
{
    public string $email = '';

    public string $password = '';

    public function rules(): array
    {
        return [
            User::FIELD_EMAIL => ['required', 'email', 'max:255'],
            User::FIELD_PASSWORD => ['required', 'string'],
        ];
    }

    public function credentials(): array
    {
        return $this->validate();
    }
}
