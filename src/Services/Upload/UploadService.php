<?php

namespace DaydreamLab\User\Services\Upload;

use Illuminate\Support\Facades\Storage;

class UploadService
{
    protected $type = 'Upload';

    public function avatar($url)
    {
        $contents = file_get_contents($url);
        $file_info = new \finfo(FILEINFO_MIME_TYPE);
        $name = str_random(64).'.'.explode('/', $file_info->buffer(file_get_contents($url)))[1];
        Storage::disk('user_image')->put($name, $contents);

        return $name;
    }
}