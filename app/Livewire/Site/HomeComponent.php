<?php

namespace App\Livewire\Site;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::public')]
class HomeComponent extends Component
{
    #[Title('Indústria de Plásticos e Moldes')]
    public function render(): View
    {
        return view('livewire.site.home', [
            'clients' => config('client.clients'),
            'yearsInMarket' => now()->year - (int) config('client.founded_year'),
        ]);
    }
}
