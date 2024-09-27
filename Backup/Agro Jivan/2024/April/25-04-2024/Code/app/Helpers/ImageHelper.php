<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class ImageHelper
{
    public static function getImageUrl($image)
    {
        $imageUrl = asset('public/assets/media/default/default.jpg');
        if ($image !== null && File::exists('public/assets/upload/' . $image)) {
            $imageUrl = asset('public/assets/upload/' . $image);
        }
        return $imageUrl;
    }
}
