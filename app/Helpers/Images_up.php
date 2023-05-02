<?php

namespace app\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
class Images_up{

    public $thumb_width  = 300;
    public $thumb_height = 300;

    public $miny_width  = 100;
    public $miny_height = 100;

    public $image_name = "images";

    public $path = "uploads";

    public function upload() {
        $files = Input::file($this->image_name);
        if(!is_array($files)){
           $files[] =  $files;
        }
         foreach ($files as $key => $file) {
             $image = $file;
             $random_string = md5(microtime());
             $filename  = $random_string.'.'. $image->getClientOriginalExtension();
             $path = public_path($this->path.'/' . $filename);
             Image::make($image->getRealPath())->save($path);

             $filename_thumb  = "thumb_".$filename;
             $path = public_path($this->path.'/' . $filename_thumb);
             Image::make($image->getRealPath())->resize($this->thumb_width, $this->thumb_height, function ($constraint) {
                 $constraint->aspectRatio();
                 $constraint->upsize();
             })->save($path);

             $filename_miny  = "miny_".$filename;
             $path = public_path($this->path.'/' . $filename_miny);
             Image::make($image->getRealPath())->resize($this->miny_width, $this->miny_height, function ($constraint) {
                 $constraint->aspectRatio();
                 $constraint->upsize();
             })->save($path);
             $images[] = $filename;
         }
         return $images;
    }
    public function deleteImages($images)
    {
        unlink(public_path($this->path.'/'.$images));
        unlink(public_path($this->path.'/miny_'.$images));
        unlink(public_path($this->path.'/thumb_'.$images));
    }
}
