<?php

namespace App\Livewire\Site;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::public')]
class ServicosComponent extends Component
{
    #[Title('Serviços')]
    public function render(): View
    {
        return view('livewire.site.servicos');
    }
}
