<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toast extends Component
{
    public function __construct(
        public string $position = 'toast-top toast-end',
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.app-toast');
    }
}
