<?php

namespace Botble\ACL\Services;

use Botble\Media\Services\UploadsManager;
use File;
use Storage;

/**
 * Class CropAvatar
 * @package Botble\CropAvatar
 * @author Sang Nguyen
 * @since 08/19/2015 07:50
 */
class CropAvatar
{
    /**
     * @var mixed
     */
    protected $src;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var mixed
     */
    protected $dst;

    /**
     * @var mixed
     */
    protected $msg;

    /**
     * @var mixed
     */
    protected $props;

    /**
     * @param $src
     * @param $data
     * @param $file
     * @author Sang Nguyen
     * @since 08/19/2015
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function __construct($src, $data, $file)
    {
        $this->src = $src;
        $this->props = $file;
        $this->setData($data);
        $this->crop($this->data);
    }

    /**
     * @param $data
     * @author Sang Nguyen
     * @since 08/19/2015
     */
    protected function setData($data)
    {
        if (!empty($data)) {
            $this->data = json_decode(stripslashes($data));
        }
    }

    /**
     * Crop avatar and set URL, return message
     *
     * @param $data
     * @author Sang Nguyen
     * @since 08/19/2015
     * @throws \League\Flysystem\FileNotFoundException
     */
    protected function crop($data)
    {
        if (!empty($data)) {
            $tmpFile = storage_path(microtime(true) . '.' . $this->props['ext']);
            $tmpThumbFile = storage_path(microtime(true) . '_thumb.' . $this->props['ext']);
            file_put_contents($tmpFile, Storage::disk()->read($this->props['path']));
            $type = exif_imagetype($tmpFile);
            $src_img = null;
            switch ($type) {
                case IMAGETYPE_GIF:
                    $src_img = imagecreatefromgif($tmpFile);
                    break;

                case IMAGETYPE_JPEG:
                    $src_img = imagecreatefromjpeg($tmpFile);
                    break;

                case IMAGETYPE_PNG:
                    $src_img = imagecreatefrompng($tmpFile);
                    break;
            }

            if (!$src_img) {
                $this->msg = trans('core/acl::users.read_image_failed');
                return;
            }

            $dst_img = imagecreatetruecolor(220, 220);
            $result = imagecopyresampled(
                $dst_img,
                $src_img,
                0,
                0,
                $data->x,
                $data->y,
                220,
                220,
                $data->width,
                $data->height
            );

            if ($result) {
                switch ($type) {
                    case IMAGETYPE_GIF:
                        $result = imagegif($dst_img, $tmpThumbFile);
                        break;

                    case IMAGETYPE_JPEG:
                        $result = imagejpeg($dst_img, $tmpThumbFile);
                        break;

                    case IMAGETYPE_PNG:
                        $result = imagepng($dst_img, $tmpThumbFile);
                        break;
                }

                if (!$result) {
                    $this->msg = trans('core/acl::profile.save_cropped_image_failed');
                    return;
                } else {
                    (new UploadsManager)->saveFile($this->props['realPath'], file_get_contents($tmpThumbFile));
                    File::delete($tmpFile);
                    File::delete($tmpThumbFile);

                    $this->dst = '/storage' . str_replace(public_path(), '', config('core.base.general.upload.base_dir')) . DIRECTORY_SEPARATOR . $this->props['realPath'];
                    $this->msg = 'success';
                }
            } else {
                $this->msg = trans('core/acl::profile.fail_to_crop_image');
                return;
            }

            imagedestroy($src_img);
            imagedestroy($dst_img);
        } else {
            $this->msg = trans('core/acl::profile.failed_to_load_data');
            return;
        }
    }

    /**
     * Get avatar URL uploaded
     * @return string
     * @author Sang Nguyen
     * @since 08/19/2015
     */
    public function getResult()
    {
        return !empty($this->data) ? $this->dst : $this->src;
    }

    /**
     * Get crop message: failed or successfully
     * @return string
     * @author Sang Nguyen
     * @since 08/19/2015
     */
    public function getMsg()
    {
        return $this->msg;
    }
}
