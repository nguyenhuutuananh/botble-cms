<?php

namespace Botble\Widget\Models;

use Botble\Base\Models\BaseModel;

class WidgetArea extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'widget_areas';

    /**
     * @var array
     */
    protected $fillable = ['belong_to', 'type', 'data'];

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
