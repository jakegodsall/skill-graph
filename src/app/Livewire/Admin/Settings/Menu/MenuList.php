<?php

namespace App\Livewire\Admin\Settings\Menu;

use App\Models\Menu;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Url;
use Livewire\Component;

class MenuList extends Component
{
    /**
     * Store menuitems list
     * @var array
     */
    public $menuitems;

    /**
     * Tabs to show
     *
     * @var array
     */
    public array $tabs = [];

    /**
     * Current tab
     *
     * @var string
     */
    #[Url(as:'tab')]
    public string $current_tab = 'admin';

    protected $listeners = [
        'create'
    ];

    /**
     * Mount livewire variables
     *
     * @return void
     */
    public function mount():void
    {
        Gate::allowIf(fn($user) => $user->is_admin && $user->can('admin manage menu'));

        $this->tabs = [
            'admin' => __('Admin'),
            'user' => __('User')
        ];

        $this->current_tab = request('tab', 'admin');
        if(!in_array($this->current_tab, array_keys($this->tabs))) $this->current_tab = 'admin';
    }

    /**
     * Render menu list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->menuitems = Menu::getMenuTree($this->current_tab == 'admin' ? 'Admin' : 'User', false, false);

        return view('admin.settings.menu.livewire-menu');
    }

    /**
     * Redirect to create screen with correct tab
     *
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function create()
    {
        return redirect()->route('admin.settings.menu.create', ['tab' => $this->current_tab]);
    }
}
