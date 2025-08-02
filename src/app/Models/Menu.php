<?php

namespace App\Models;

use App\Models\Traits\MorphLink;
use App\Models\Traits\SaveAudit;
use App\Models\Traits\ScopeActive;
use App\Models\Traits\ScopePermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    use HasFactory, SoftDeletes, ScopeActive, MorphLink, ScopePermission, SaveAudit;

    static ?array $menuTree = null;

    public $table = 'menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'icon', 'linkable_id', 'linkable_type', 'link', 'dropdownOnly', 'internal', 'active', 'parent_id', 'pos', 'all_permissions', 'type'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dropdownOnly'      => 'boolean',
        'internal'          => 'boolean',
        'active'            => 'boolean',
        'all_permissions'   => 'boolean',
    ];


    /**
     * Link to permissions
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'menu_permissions');
    }

    /**
     * Builds entire menu tree
     *
     * @param bool $activeOnly
     * @param bool $userOnly
     * @return array
     */
    public static function getMenuTree($type='User', $activeOnly=true, $userOnly=true): ?array
    {
        if($activeOnly && !is_null(static::$menuTree)) return static::$menuTree;

        $request = request();
        $user = $request->user();
        if($userOnly && is_null($user)) return [];

        $q = static::select()->where('type', $type)->with('linkable.permissions');
        if ($activeOnly) $q->active();

        if ($userOnly && !$user->hasRole('Super Admin')) {
            $permissions = Permission::whereIn('name', $user->getAllPermissions()->pluck('name'))->pluck('id');
            $q->permission($permissions);
            $q->linkablePermission($permissions);
        }

        $links = $q->orderby('parent_id')->orderby('pos')->get()->keyBy('id');

        // set on link
        foreach($links as $i => $link)
        {
            $link->url = $link->getUrlAttribute(); // force url for when we convert to array
            $link_parts = parse_url($link->url);
            $path = $link_parts['path'] ?? (isset($link_parts['host']) ? '/' : null);

            $qs = [];
            if(isset($link_parts['query'])){
                parse_str($link_parts['query'], $qs);
                ksort($qs);
            }

            $link->on = false;
            $link->hasChildren = false;
            if($link->internal){
                $querystring = false;
                $exact = $link->parent_id != null;

                if(isset(\App\Providers\AppServiceProvider::$menuSettings[rtrim($path, '/')]))
                {
                    $s = \App\Providers\AppServiceProvider::$menuSettings[rtrim($path, '/')];
                    $querystring = $s['querystring'] ?? $querystring;
                    $exact = $s['exact'] ?? $exact;
                }

                if($path == '/'){
                    if($request->is('/') || $request->is('admin/dashboard') || $request->is('featured-collections*')) $link->on = true;
                } elseif(str_starts_with($path, '/')
                    && ($exact ? $request->is(trim($path, '/')) : $request->is(trim($path, '/').'*'))
                    && (!$querystring || $qs == $_GET)
                ){
                    $link->on = true;
                } elseif(isset($GLOBALS['SET_LINK_ON']) && $GLOBALS['SET_LINK_ON'] == $path) {
                    $link->on = true;
                }
            }

            $link->children = [];
        }

        // this will convert links to array as that is only way this will work
        $links = static::makeNested($links);
        if($activeOnly) static::$menuTree = $links;

        return $links;
    }

    /**
     * Adds multiple depths to navigation
     *
     * @param $source
     * @return array
     */
    private static function makeNested($source): array
    {
        $source = $source->toArray();
        $nested = array();

        foreach ( $source as &$s ) {
            if ( is_null($s['parent_id']) ) {
                // no parent_id so we put it in the root of the array
                $nested[] = &$s;
            }
            else {
                $pid = $s['parent_id'];
                if ( isset($source[$pid]) ) {

                    $source[$pid]['children'][] = &$s;
                    if($s['on']) static::setParentsOn($source, $s);
                    if(!$s['dropdownOnly']) static::setParentsHasChildren($source, $s);
                }
            }
        }

        return $nested;
    }

    /**
     * If child is active also make all the parent dropdowns active
     *
     * @param $source
     * @param $s
     */
    private static function setParentsOn(&$source, $s): void
    {
        $pid = $s['parent_id'];
        if(!is_null($pid) && isset($source[$pid]))
        {
            $source[$pid]['on'] = true;
            static::setParentsOn($source, $source[$pid]);
        }
    }

    /**
     * Add a markes so be can easily tell if a parent has active children
     *
     * @param $source
     * @param $s
     */
    private static function setParentsHasChildren(&$source, $s): void
    {
        $pid = $s['parent_id'];
        if(!is_null($pid) && isset($source[$pid]))
        {
            $source[$pid]['hasChildren'] = true;
            static::setParentsHasChildren($source, $source[$pid]);
        }
    }
}
