<?php

namespace Botble\Media\Models;

use Exception;
use Botble\Base\Models\BaseModel;

class MediaSetting extends BaseModel
{
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'media_settings';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
        'user_id',
    ];

    /**
     * @param $value
     * @return array
     */
    public function getValueAttribute($value)
    {
        try {
            return json_decode($value, true) ?: [];
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @param $value
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = json_encode($value);
    }
}
