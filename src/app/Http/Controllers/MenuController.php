<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\CustomerPermission;
use App\Models\CustomerType;
use App\Models\Menu;
use App\Models\MenuContext;
use App\Models\MenuCustomerType;
use App\Models\Type;
use App\Models\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    private $rules = [];
    private $messages = [];

    public function __construct()
    {
        Gate::allowIf(fn($user) => $user->is_admin && $user->can('admin manage menu'));

        // setup validation rules
        $this->rules = [
            'title'                         => ['required'],
        ];

        $this->messages = [
            'title.required'                => 'Please enter a title',
            'link.required'                 => 'Please enter a link',
            'link.url'                      => 'Please enter a valid link'
        ];
    }


    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.settings.menu.index');
    }

    /**
     * Update sorting positions for menu (drag & drop)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sort(Request $request): JsonResponse
    {
        if($request->filled('menu'))
        {
            $menuitem = $request->input('menu');
            $this->_sortLoop($menuitem);

            return response()->json(['success'=>true]);
        }
    }

    /**
     * Loop through all rows and children
     *
     * @param $rows
     * @param null $parent_id
     */
    private function _sortLoop($rows, $parent_id=null): void
    {
        foreach($rows as $pos => $p)
        {
            $m = Menu::findOrFail($p['id']);
            $m->parent_id = $parent_id;
            $m->pos = $pos+1;
            $m->save();

            if(isset($p['children']))
                $this->_sortLoop($p['children'], $p['id']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $menuitem = new Menu();
        return $this->_addEditForm('admin.settings.menu.create', $menuitem);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // if external link then check it is valid
        if(!$request->get('dropdownOnly') && !$request->get('internal')) $this->rules['link'] = ['required', 'url'];

        $request->validate($this->rules, $this->messages);
        $input = $request->all();

        $menuitem = new Menu($input);

        $perms = $input['permissions'] ?? [];

        $menuitem->pos = Menu::where('parent_id', $menuitem->parent_id)->max('pos')+1; // set position
        $menuitem->save();

        $permissions = Permission::whereIn('name', $perms)->pluck('id')->toArray();
        $menuitem->permissions()->sync($permissions);

        // save audit with extra
        $menuitem->saveAudit('Create', [], ['permissions' => $perms]);

        return redirect()->route('admin.settings.menu.index', ['tab' => $menuitem->type=='Admin' ? 'admin' : 'user'])->with([
            'flash.banner'      => "Created Menu Item '" . $menuitem->title . "'",
            'flash.bannerStyle' => 'success'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id): View
    {
        $menuitem = Menu::findOrFail($id);
        return $this->_addEditForm('admin.settings.menu.edit', $menuitem);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // if external link then check it is valid
        if(!$request->get('dropdownOnly') && !$request->get('internal')) $this->rules['link'] = ['required', 'url'];

        $request->validate($this->rules, $this->messages);
        $input = $request->all();

        $menuitem = Menu::findOrFail($id);

        $orig_perms = $menuitem->permissions()->pluck('name')->toArray();
        $perms = $input['permissions'] ?? [];

        $menuitem->update($input);

        // permissions to id
        $permissions = Permission::whereIn('name', $perms)->pluck('id')->toArray();
        $menuitem->permissions()->sync($permissions);

        $audit_original = [];
        $audit_changed = [];

        // save audit with extra
        if($orig_perms != $perms){
            $audit_original['permissions'] = implode(', ', $orig_perms);
            $audit_changed['permissions'] = implode(', ', $perms);
        }

        $menuitem->saveAudit('Update', $audit_original, $audit_changed);

        return redirect()->route('admin.settings.menu.index', ['tab' => $menuitem->type=='Admin' ? 'admin' : 'user'])->with([
            'flash.banner'      => "Updated Menu Item '" . $menuitem->title . "'",
            'flash.bannerStyle' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $menuitem = Menu::findOrFail($id);

        $orig_perms = $menuitem->permissions()->pluck('name')->toArray();

        $menuitem->delete();

        return redirect()->route('admin.settings.menu.index', ['tab' => $menuitem->type=='Admin' ? 'admin' : 'user'])->with([
            'flash.banner'      => "Deleted Menu Item '" . $menuitem->title . "'",
            'flash.bannerStyle' => 'success'
        ]);
    }

    /**
     * Get correct form
     *
     * @param string $view
     * @param Menu $menuitem
     * @return View
     */
    private function _addEditForm(string $view, Menu &$menuitem): View
    {
        $permissions = User::getPermissionTable();

        $current_permissions = $menuitem->permissions()->pluck('name')->toArray();

        return view($view, compact('menuitem', 'permissions', 'current_permissions'));
    }
}
