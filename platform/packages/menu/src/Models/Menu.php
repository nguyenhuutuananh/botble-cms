<?php

namespace Botble\Menu\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Base\Models\BaseModel;

class Menu extends BaseModel
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
     */
    public function menuNodes()
    {
        return $this->hasMany(MenuNode::class, 'menu_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany(MenuLocation::class, 'menu_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Menu $menu) {
            MenuNode::where('menu_id', $menu->id)->delete();
        });
    }
}
