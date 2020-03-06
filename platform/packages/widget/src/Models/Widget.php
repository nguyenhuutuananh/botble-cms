<?php

namespace Botble\Widget\Models;

use Botble\Base\Models\BaseModel;

class Widget extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'widgets';

    /**
     * @var array
     */
    protected $fillable = [
        'widget_id',
        'sidebar_id',
        'theme',
        'position',
        'data',
    ];

    /**
     * @param $value
     */
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }
}
