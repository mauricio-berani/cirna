<?php

namespace App\Livewire\Site;

use App\Models\Common\Client;
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
            'clients' => Client::query()->orderBy(Client::FIELD_NAME)->get(),
        ]);
    }
}
