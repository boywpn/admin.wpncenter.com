<?php

namespace Modules\Platform\MenuManager\Entities;

use Modules\Platform\Core\Entities\CachableModel;

/**
 * Menu Entity
 * 
 * Class Menu
 *
 * @package Modules\Platform\MenuManager\Entities
 * @property int $id
 * @property int $order_by
 * @property string $url
 * @property string $label
 * @property string $icon
 * @property string|null $permission
 * @property int|null $parent_id
 * @property int $section
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $dont_translate
 * @property int|null $visibility
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\MenuManager\Entities\Menu[] $children
 * @property-read \Modules\Platform\MenuManager\Entities\Menu|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu whereDontTranslate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu whereOrderBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu whereVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\MenuManager\Entities\Menu query()
 */
class Menu extends CachableModel
{
    protected $table = 'bap_menu';

    protected $fillable = [
        'url',
        'label',
        'icon',
        'permission',
        'parent_id',
        'section',
        'visibility',
        'dont_translate'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'id' => 'integer',
        'label' => 'string',
        'icon' => 'string',
        'permission' => 'string',
        'parent_id' => 'integer',
        'section' => 'integer'
    ];

    /**
     * Parent menu element
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Children of menu element
     *
     * @return $this
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order_by', 'asc');
    }
}
