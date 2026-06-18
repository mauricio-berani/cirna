<?php

namespace App\Livewire\Site;

use App\Models\Common\Client;
use App\Models\Common\Setting;
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
        $showClients = Setting::showClientsSection();

        return view('livewire.site.home', [
            'showClients' => $showClients,
            'clients' => $showClients
                ? Client::query()->orderBy(Client::FIELD_NAME)->get()
                : collect(),
            'yearsInMarket' => now()->year - (int) config('client.founded_year'),
        ]);
    }
}
