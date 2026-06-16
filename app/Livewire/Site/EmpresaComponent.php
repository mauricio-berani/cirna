<?php

namespace App\Livewire\Site;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::public')]
class EmpresaComponent extends Component
{
    #[Title('Empresa')]
    public function render(): View
    {
        return view('livewire.site.empresa');
    }
}
