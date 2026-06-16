<?php

namespace App\Livewire\Site;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::public')]
class ClientesComponent extends Component
{
    #[Title('Clientes')]
    public function render(): View
    {
        return view('livewire.site.clientes', [
            'clients' => config('client.clients'),
        ]);
    }
}
