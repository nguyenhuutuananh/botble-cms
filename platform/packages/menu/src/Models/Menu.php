<?php

namespace Botble\Menu\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Eloquent;

class Menu extends Eloquent
{

    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Sang Nguyen
     */
    public function menuNodes()
    {
        return $this->hasMany(MenuNode::class, 'menu_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Sang Nguyen
     */
    public function locations()
    {
        return $this->hasMany(MenuLocation::class, 'menu_id');
    }

    /**
     * @author Sang Nguyen
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Menu $menu) {
            MenuNode::where('menu_id', $menu->id)->delete();
        });
    }
}
