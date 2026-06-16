<?php

namespace App\Livewire\Forms\Auth;

use App\Enums\Auth\Permissions;
use App\Enums\Auth\Roles;
use App\Models\Auth\User;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $model = null;

    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public ?string $avatar = null;

    public string $password = '';

    public string $password_confirmation = '';

    public string $user_role = '';

    public array $permissions = [];

    public function rules(): array
    {
        $modelId = $this->model?->id;

        return [
            User::FIELD_NAME => ['required', 'string', 'min:3', 'max:255'],

            User::FIELD_EMAIL => [
                'required',
                'email',
                'max:255',
                Rule::unique(User::TABLE, User::FIELD_EMAIL)->ignore($modelId),
            ],

            User::FIELD_PHONE => ['nullable', 'string', 'max:20'],

            User::APPEND_FIELD_USER_ROLE => ['required', new Enum(Roles::class)],

            User::APPEND_FIELD_PERMISSIONS => ['array'],
            User::APPEND_FIELD_PERMISSIONS.'.*' => [Rule::in(array_map(fn ($item) => $item->value, Permissions::cases()))],

            User::FIELD_PASSWORD => [
                $modelId ? 'nullable' : 'required',
                Password::min(12)->letters()->mixedCase()->numbers()->symbols()->uncompromised(3),
                'confirmed',
            ],

            User::APPEND_FIELD_PASSWORD => [
                $modelId ? 'nullable' : 'required',
                'same:password',
            ],
        ];
    }

    public function setUser(User $user): void
    {
        $this->model = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->avatar = $user->avatar;
        $this->password = '';
        $this->password_confirmation = '';
        $this->user_role = $user->roles->first()?->name ?? '';
        $this->permissions = $user->getAllPermissions()->pluck('name')->values()->all();
    }

    public function payload(): array
    {
        $validated = $this->validate();

        $role = $validated[User::APPEND_FIELD_USER_ROLE];
        $permissions = Arr::wrap($validated[User::APPEND_FIELD_PERMISSIONS] ?? []);

        unset($validated[User::APPEND_FIELD_USER_ROLE], $validated[User::APPEND_FIELD_PERMISSIONS]);

        if (empty($validated[User::FIELD_PASSWORD] ?? null)) {
            unset($validated[User::FIELD_PASSWORD], $validated[User::APPEND_FIELD_PASSWORD]);
        }

        $data = collect($validated)
            ->filter(fn ($value) => ! is_null($value))
            ->all();

        return [
            'data' => $data,
            'role' => $role,
            'permissions' => $permissions,
        ];
    }
}
