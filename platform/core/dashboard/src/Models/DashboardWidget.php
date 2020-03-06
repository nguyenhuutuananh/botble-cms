<?php

namespace Botble\Dashboard\Models;

use Botble\Base\Models\BaseModel;

class DashboardWidget extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dashboard_widgets';

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function settings()
    {
        return $this->hasMany(DashboardWidgetSetting::class, 'widget_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (DashboardWidget $widget) {
            DashboardWidgetSetting::where('widget_id', $widget->id)->delete();
        });
    }
}
