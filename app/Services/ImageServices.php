<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageServices
{
    public static function upload($file, $pathName){
        $extension =  $file->getClientOriginalExtension();
        $fileName = time() . "." . $extension;
        return $file->storeAs($pathName, $fileName, 'public');
    }
}   