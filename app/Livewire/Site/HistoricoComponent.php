<?php

namespace App\Livewire\Site;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::public')]
class HistoricoComponent extends Component
{
    #[Title('Histórico')]
    public function render(): View
    {
        return view('livewire.site.historico', [
            'yearsInMarket' => now()->year - (int) config('client.founded_year'),
        ]);
    }
}
