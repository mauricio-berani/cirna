<?php

namespace App\Livewire\Site;

use App\Models\Common\Setting;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::public')]
class QualidadeComponent extends Component
{
    #[Title('Qualidade')]
    public function render(): View
    {
        return view('livewire.site.qualidade', [
            'certificateUrl' => Setting::isoCertificatePath() ? route('site.certificate') : null,
        ]);
    }
}
