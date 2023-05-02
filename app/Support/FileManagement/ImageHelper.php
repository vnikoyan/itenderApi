<?php

// Define the namespace
namespace App\Support\FileManagement;

// Include any required classes, interfaces etc...
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;

class ImageHelper
{
    /**
     * Instance of Intervention ImageManager.
     *
     * @see https://github.com/Intervention/image
     * @var ImageManager
     */
    protected $image_manager;

    /**
     * Image object created by Intervention.
     *
     * @var \Intervention\Image\Image
     */
    protected $image;

    /**
     * Path to the directory the image will be uploaded.
     *
     * @var string
     */
    protected $path;

    /**
     * The filename of the currently manipulated image.
     *
     * @var string
     */
    protected $filename;

    /**
     * ImageHelper Class Constructor.
     *
     * @param string $path
     * @param string $driver
     */
    public function __construct($path = null, $driver = 'gd')
    {
        $this->path = $path;
        $this->image_manager = new ImageManager(['driver' => $driver]);
    }

    /**
     * Stream to string.
     *
     * @param   string  $source
     * @param   array   $sizes
     * @return  array|mixed|string
     */
    public function encode($source, $sizes = [])
    {
        $this->image = $this->image_manager->make($source);

        if (empty($sizes)) {
            return $this->encodeOriginal();
        }else{
            return $this->encodeSizes($sizes);
        }
    }

    /**
     * Stream original.
     *
     * @return string
     */
    protected function encodeOriginal()
    {
        $image = (string) $this->image->encode();

        return array(
            'name'      => 'original',
            'filename'  => FileManager::generateUniqueFilename('original') . $this->getExtension(),
            'image'     => $image
        );
    }

    /**
     * Stream sizes.
     *
     * @param   array $sizes
     * @return  array
     */
    protected function encodeSizes($sizes = [])
    {
        $images = [];
        foreach ($sizes as $size) {
            $images[$size['name']] = call_user_func([$this, $size['transform']], $size);
        }

        return $images;
    }

    /**
     * Get extension.
     *
     * @return string
     */
    protected function getExtension()
    {
        switch ($this->image->mime()) {
            case 'image/jpeg' :
                return '.jpg';
            case 'image/png' :
                return '.png';
            case 'image/gif' :
                return '.gif';
            default :
                return false;
        }
    }

    /**
     * Fit the image into a mask and crop the offset.
     *
     * @param   array $size
     * @return  string
     */
    protected function crop($size)
    {
        $filename = FileManager::generateUniqueFilename($size['name']) . $this->getExtension();

        $this->orientate($this->image, $this->image->exif('Orientation'));

        $image = clone $this->image;
        $image->fit($size['width'], $size['height']);

        return array(
            'name'      => $size['name'],
            'filename'  => $filename,
            'image'     => (string) $image->encode()
        );
    }

    /**
     * Resize the image based on the supplied width.
     *
     * @param   array $size
     * @return  string
     */
    protected function width($size)
    {
        $filename = FileManager::generateUniqueFilename($size['name']) . $this->getExtension();

        $image = clone $this->image;
        $image->resize($size['width'], null, function ($constraint) {
            $constraint->aspectRatio();
        });

        return array(
            'name'      => $size['name'],
            'filename'  => $filename,
            'image'     => (string) $image->encode()
        );
    }

    /**
     * Orientate.
     *
     * @param   \Intervention\Image\Image
     * @param   integer  $orientation
     * @return  \Intervention\Image\Image
     */
    protected function orientate($img, $orientation)
    {
        switch ($orientation) {
            case 1:
                return $img;
            case 2:
                return $img->flip('h');
            case 3:
                return $img->rotate(180);
            case 4:
                return $img->rotate(180)->flip('h');
            case 5:
                return $img->rotate(-90)->flip('h');
            case 6:
                return $img->rotate(-90);
            case 7:
                return $img->rotate(-90)->flip('v');
            case 8:
                return $img->rotate(90);

            default:
                return $img;
        }
    }
}