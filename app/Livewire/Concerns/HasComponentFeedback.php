<?php

namespace App\Livewire\Concerns;

use Mary\Traits\Toast;

trait HasComponentFeedback
{
    use Toast;

    protected function toastSuccess(string $message, ?string $redirectRoute = null): void
    {
        $this->success(
            title: $message,
            position: 'toast-center',
            timeout: 3000,
            redirectTo: $redirectRoute
        );
    }

    protected function toastError(string $message): void
    {
        $this->error(
            title: $message,
            position: 'toast-center'
        );
    }
}
