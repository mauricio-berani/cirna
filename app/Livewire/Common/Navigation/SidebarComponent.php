<?php

namespace App\Livewire\Common\Navigation;

use App\Contracts\Navigation\BuildsSidebarMenus;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SidebarComponent extends Component
{
    public Authenticatable $user;

    public array $menus = [];

    protected BuildsSidebarMenus $buildSidebarMenus;

    public function boot(BuildsSidebarMenus $buildSidebarMenus): void
    {
        $this->buildSidebarMenus = $buildSidebarMenus;
    }

    public function mount(): void
    {
        /** @var Authenticatable $user * */
        $this->user = logged_user();
        $this->menus = $this->buildSidebarMenus->handle(
            $this->user,
            (string) request()->route()?->getName()
        );
    }

    public function render(): View
    {
        return view('livewire.common.navigation.sidebar-component', [
            'menus' => $this->menus,
        ]);
    }
}
