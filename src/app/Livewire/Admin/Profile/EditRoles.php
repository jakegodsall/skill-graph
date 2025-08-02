<?php

namespace App\Livewire\Admin\Profile;

use App\Models\Auth\User;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EditRoles extends Component
{
    public User $user;
    public array $current_roles = [];
    public Collection $roles;

    protected $listeners = [
        'updated-roles' => 'onUpdatedRoles'
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->current_roles = $this->user->getRoleNames()->toArray();
        $this->roles = Role::all()->pluck('name');
    }

    public function toggleRole($role): void
    {
        if(!in_array($role, $this->current_roles)) $this->current_roles[] = $role;
        else unset($this->current_roles[array_search($role, $this->current_roles, true)]);
    }

    public function updateRole(): void
    {
        $this->resetErrorBag();

        $orig_roles = $this->user->getRoleNames()->toArray();

        $this->user->save();

        // for some bizarre reason on aws the roles will detach but wont attach correctly
        // so we're going to do this manually for that reason
        //$this->user->syncRoles($this->current_roles);

        $roles = collect($this->current_roles)
            ->flatten()
            ->reduce(function ($array, $role) {
                if (empty($role)) {
                    return $array;
                }

                $role = Role::findByName($role, 'web');

                $array[$role->getKey()] = [];
                return $array;
            }, []);

        $this->user->roles()->sync($roles);

        // clear permission cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->dispatch('updated-roles');
    }

    public function render(): View
    {
        return view('livewire.admin.profile.edit-roles');
    }

    public function onUpdatedRoles()
    {
        session()->flash('message', 'Roles updated successfully!');
        return redirect()->route('admin.user.edit', $this->user->id);
    }
}
