<?php

namespace App\Livewire\Auth\User;

use App\Contracts\Auth\BuildsPermissionOptions;
use App\Contracts\Auth\CreatesUsers;
use App\Contracts\Auth\UpdatesUsers;
use App\Enums\Auth\Roles;
use App\Livewire\FormBaseComponent;
use App\Livewire\Forms\Auth\UserForm;
use App\Models\Auth\User;
use Livewire\Attributes\Layout;
use Throwable;

#[Layout('layouts::app')]
class FormComponent extends FormBaseComponent
{
    public UserForm $form;

    public string $tab = 'general';

    public bool $editingSelf = false;

    protected CreatesUsers $createUserService;

    protected UpdatesUsers $updateUserService;

    protected BuildsPermissionOptions $buildPermissionOptions;

    public function boot(
        CreatesUsers $createUserService,
        UpdatesUsers $updateUserService,
        BuildsPermissionOptions $buildPermissionOptions
    ): void {
        $this->createUserService = $createUserService;
        $this->updateUserService = $updateUserService;
        $this->buildPermissionOptions = $buildPermissionOptions;
    }

    protected function getModelClass(): string
    {
        return User::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'users';
    }

    protected function getViewPath(): string
    {
        return 'livewire.auth.user.form';
    }

    protected function getUpdateRoute(): ?string
    {
        return route('users.update', ['itemId' => $this->item?->getKey()]);
    }

    protected function getIndexTitle(): string
    {
        return __('interface.identification.users.title');
    }

    public function setCustomViewData(): array
    {
        return [
            'editingSelf' => $this->editingSelf,
            'roleOptions' => Roles::options(),
            'permissionOptions' => $this->buildPermissionOptions->handle(),
        ];
    }

    public function afterSetItem(): void
    {
        if ($this->updating && $this->item) {
            $this->editingSelf = logged_user()->is($this->item);
            $this->form->setUser($this->item);
        }
    }

    public function save(): void
    {
        if ($this->updating) {
            $this->update();

            return;
        }

        $this->create();
    }

    public function create(): void
    {
        $this->authorize('create', $this->getModelClass());
        $payload = $this->form->payload();

        try {
            $this->item = $this->createUserService->handle($payload);
            $this->toastSuccess(__('feedback.create_success'), $this->getUpdateRoute());
        } catch (Throwable $error) {
            logger()->error($error->getMessage());
            $this->toastError(__('feedback.create_error'));
        }
    }

    public function update(): void
    {
        $this->authorize('update', $this->item);
        $payload = $this->form->payload();

        try {
            $this->item = $this->updateUserService->handle(
                $this->item,
                $payload,
                ! $this->editingSelf
            );

            $this->toastSuccess(__('feedback.update_success'));
        } catch (Throwable $error) {
            logger()->error($error->getMessage());
            $this->toastError(__('feedback.update_error'));
        }
    }
}
