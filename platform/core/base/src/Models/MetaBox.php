<?php

namespace Botble\Base\Models;

class MetaBox extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'meta_boxes';

    /**
     * @param $value
     */
    public function setMetaValueAttribute($value)
    {
        $this->attributes['meta_value'] = json_encode($value);
    }

    /**
     * @param $value
     * @return array
     */
    public function getMetaValueAttribute($value)
    {
        return json_decode($value, true);
    }
}
