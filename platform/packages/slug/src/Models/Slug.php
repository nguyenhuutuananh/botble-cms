<?php

namespace Botble\Slug\Models;

use Botble\Base\Models\BaseModel;

class Slug extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'slugs';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'reference',
        'reference_id',
        'prefix',
    ];
}
