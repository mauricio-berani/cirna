<?php

namespace App\Livewire\Auth\Profile;

use App\Contracts\Auth\UpdatesProfiles;
use App\Enums\Common\ComponentEvents;
use App\Livewire\BaseComponent;
use App\Livewire\Forms\Auth\ProfileUpdateForm;
use App\Models\Auth\User;
use App\Models\Auth\User as Model;
use App\Traits\ManagesFilesTrait;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

#[Layout('layouts::app')]
#[Title('Perfil')]
class UpdateComponent extends BaseComponent
{
    use ManagesFilesTrait;
    use WithFileUploads;

    public ProfileUpdateForm $form;

    public string $title = 'Atualizar';

    public string $subtitle = 'Atualize suas informações';

    public User $item;

    public string $filePath = 'avatars';

    public ?string $avatarPath = null;

    protected UpdatesProfiles $updateProfileService;

    public function boot(UpdatesProfiles $updateProfileService): void
    {
        $this->updateProfileService = $updateProfileService;
    }

    protected function getModelClass(): string
    {
        return Model::class;
    }

    protected function getRoutePrefix(): string
    {
        return 'profile';
    }

    protected function getViewPath(): string
    {
        return 'livewire.auth.profile.update-component';
    }

    public function getBreadcrumbs(): array
    {
        return [
            ['title' => 'Perfil'],
        ];
    }

    public function update(): void
    {
        $this->authorize('updateProfile', Model::class);
        $payload = $this->form->payload();

        try {
            $this->item = $this->updateProfileService->handle($this->item, $payload);
            $this->form->setUser($this->item);
            $this->avatarPath = $this->getFileUrl($this->item->avatar);
            $this->dispatch(ComponentEvents::PROFILE_UPDATED->value);
            $this->success(__('feedback.update_success'), position: 'toast-top');
        } catch (\Throwable $error) {
            logger()->error($error->getMessage());
            $this->error(__('feedback.update_error'), position: 'toast-top');
        }
    }

    public function mount(): void
    {
        $this->authorize('mountProfile', Model::class);

        $this->item = logged_user();
        $this->form->setUser($this->item);
        $this->avatarPath = $this->getFileUrl($this->item->avatar);
    }

    public function render(): View
    {
        return view('livewire.auth.profile.update-component', [
            'defaultAvatar' => asset('assets/images/no-avatar.png'),
            'breadcrumbs' => $this->getBreadcrumbs(),
        ]);
    }
}
