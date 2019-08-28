<?php

if (!function_exists('get_backup_size')) {
    /**
     * @param $key
     * @return int
     * @author Sang Nguyen
     */
    function get_backup_size($key)
    {
        $size = 0;

        foreach (File::allFiles(storage_path('app/backup/' . $key)) as $file) {
            $size += $file->getSize();
        }

        return $size;
    }
}
