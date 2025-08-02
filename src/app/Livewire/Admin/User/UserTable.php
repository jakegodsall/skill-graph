<?php

namespace App\Livewire\Admin\User;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class UserTable extends DataTableComponent
{
    public function builder(): Builder
    {
        $q = User::with(['roles']);
            
        return $q;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Email", "email")
                ->sortable(),
            Column::make('2FA Enabled', 'two_factor_confirmed_at')
                ->format(fn ($row) => $row ? 'True' : 'False')
                ->sortable(),
            Column::make('Roles')
                ->label(fn ($row, Column $column) => $row->roleString),
            Column::make('Actions')
                ->label(
                    fn($row, Column $column) => '
                    <span class="space-x-1">
                        <a href="' . route('admin.user.edit', $row) . '" class="btn btn-sm btn-primary"><span class="inline-block"><i class="fa-fw fas fa-edit"></i></span></a>
                        ' . (request()->user()->hasRole('Super Admin') && request()->user()->id != $row->id ? '<form class="inline" action="' . route('admin.users.impersonate', $row) . '" method="post"> ' . csrf_field() . ' <button type="submit" class="btn btn-sm btn-secondary"><span class="inline-block"><i class="fa-fw far fa-sign-in"></i></span></button></form>' : '') . '
                    </span>'
                )->html()
        ];
    }
}
