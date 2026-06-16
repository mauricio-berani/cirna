<?php

namespace App\Livewire\Forms\Auth;

use App\Models\Auth\User;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\Password;
use Livewire\Form;

class ProfileUpdateForm extends Form
{
    public ?User $model = null;

    public string $name = '';

    public mixed $avatar = null;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function rules(): array
    {
        return [
            User::FIELD_NAME => ['required', 'string', 'min:3'],
            User::FIELD_AVATAR => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'mimetypes:image/jpeg,image/png,image/gif,image/webp', 'extensions:jpg,jpeg,png,gif,webp', 'max:1024'],
            User::APPEND_FIELD_CURRENT_PASSWORD => ['nullable', 'required_with:password', 'current_password'],
            User::FIELD_PASSWORD => ['nullable', Password::min(12)->letters()->mixedCase()->numbers()->symbols()->uncompromised(3), 'confirmed'],
            User::APPEND_FIELD_PASSWORD => ['nullable', 'same:password'],
        ];
    }

    public function setUser(User $user): void
    {
        $this->model = $user;
        $this->name = $user->name;
        $this->avatar = null;
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function payload(): array
    {
        $validated = $this->validate();

        $avatar = $validated[User::FIELD_AVATAR] ?? null;

        if (empty($validated[User::FIELD_PASSWORD] ?? null)) {
            unset(
                $validated[User::APPEND_FIELD_CURRENT_PASSWORD],
                $validated[User::FIELD_PASSWORD],
                $validated[User::APPEND_FIELD_PASSWORD]
            );
        }

        return [
            'data' => Arr::except($validated, [User::FIELD_AVATAR, User::APPEND_FIELD_CURRENT_PASSWORD]),
            'avatar' => $avatar,
        ];
    }
}
