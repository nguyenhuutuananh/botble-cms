<?php

namespace Botble\Media\Models;

use Botble\Media\Services\UploadsManager;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaFile extends BaseModel
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'media_files';

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'mime_type',
        'type',
        'size',
        'url',
        'options',
        'folder_id',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function folder()
    {
        return $this->belongsTo(MediaFolder::class, 'id', 'folder_id');
    }

    /**
     * @return string
     */
    public function getTypeAttribute()
    {
        $type = 'document';
        if ($this->attributes['mime_type'] == 'youtube') {
            return 'video';
        }

        foreach (config('media.mime_types') as $key => $value) {
            if (in_array($this->attributes['mime_type'], $value)) {
                $type = $key;
                break;
            }
        }

        return $type;
    }

    /**
     * @return string
     */
    public function getHumanSizeAttribute()
    {
        return human_file_size($this->attributes['size']);
    }

    /**
     * @return string
     */
    public function getIconAttribute()
    {
        switch ($this->type) {
            case 'image':
                $icon = 'far fa-file-image';
                break;
            case 'video':
                $icon = 'far fa-file-video';
                break;
            case 'pdf':
                $icon = 'far fa-file-pdf';
                break;
            case 'excel':
                $icon = 'far fa-file-excel';
                break;
            case 'youtube':
                $icon = 'fab fa-youtube';
                break;
            default:
                $icon = 'far fa-file-alt';
                break;
        }
        return $icon;
    }

    /**
     * @param $value
     * @return array
     */
    public function getOptionsAttribute($value)
    {
        return json_decode($value, true) ?: [];
    }

    /**
     * @param $value
     */
    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = json_encode($value);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($file) {
            /**
             * @var MediaFile $file
             */
            if ($file->isForceDeleting()) {
                $uploadManager = new UploadsManager;
                $path = str_replace(config('media.driver.' . config('filesystems.default') . '.path'), '', $file->url);
                $uploadManager->deleteFile($path);
            }
        });
    }
}
