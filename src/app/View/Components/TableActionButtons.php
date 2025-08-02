<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;

class TableActionButtons extends Component
{
    public $model;
    public $modelIdKey;
    public $showRoute;
    public $editRoute;
    public $deleteEvent;
    public $canShow;
    public $canEdit;
    public $canDelete;
    public $confirmMessage;

    /**
     * Create a new component instance.
     *
     * @param mixed $model
     * @param string $modelIdKey
     * @param string|null $showRoute
     * @param string|null $editRoute
     * @param string|null $deleteEvent
     * @param bool $canShow
     * @param bool $canEdit
     * @param bool $canDelete
     * @param string $confirmMessage
     */
    public function __construct(
        $model,
        $modelIdKey = 'id',
        $showRoute = null,
        $editRoute = null,
        $deleteEvent = null,
        $canShow = true,
        $canEdit = true,
        $canDelete = true,
        $confirmMessage = 'Are you sure you want to delete this item?'
    ) {
        $this->model = $model;
        $this->modelIdKey = $modelIdKey;
        $this->showRoute = $showRoute;
        $this->editRoute = $editRoute;
        $this->deleteEvent = $deleteEvent;
        $this->canShow = $canShow && Gate::allows('view', $model);
        $this->canEdit = $canEdit && Gate::allows('edit', $model);
        $this->canDelete = $canDelete && Gate::allows('delete', $model);
        $this->confirmMessage = $confirmMessage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-action-buttons');
    }
}
