<?php

namespace Botble\Menu\Models;

use Botble\Base\Models\BaseModel;

class MenuLocation extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu_locations';

    /**
     * @var array
     */
    protected $fillable = [
        'menu_id',
        'location',
    ];

    /**
     * @return mixed
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id')->withDefault();
    }
}
