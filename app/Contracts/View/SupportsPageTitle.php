<?php

namespace App\Contracts\View;

use Illuminate\View\View;

interface SupportsPageTitle
{
    public function title(string $title): View;
}
