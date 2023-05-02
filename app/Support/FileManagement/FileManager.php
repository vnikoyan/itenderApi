<?php

// Define the namespace
namespace App\Support\FileManagement;

// Include any required classes, interfaces etc...
use Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;

class FileManager
{
    /**
     * Instance of the Filesystem class.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The disk to use.
     *
     * @var string
     */
    protected $disk;

    /**
     * Image manipulating helper class.
     *
     * @var ImageHelper
     */
    protected $image_helper;

    /**
     * FileManager Class Constructor.
     *
     * @param string $disk
     */
    public function __construct($disk)
    {
        $this->disk = $disk;
        $this->filesystem = app()->make('filesystem')->disk($disk);
    }

    /**
     * Delete file.
     *
     * @param   string  $filename
     * @param   string  $path
     * @return  void
     */
    public function deleteFile($filename, $path)
    {
        $this->filesystem->delete(self::buildPath($path, $filename));
    }

    /**
     * Rename file.
     *
     * @param   string  $path
     * @param   string  $old_filename
     * @param   string  $new_filename
     * @return  boolean
     */
    public function renameFile($path, $old_filename, $new_filename)
    {
        if ($this->filesystem->exists(self::buildPath($path, $old_filename))) {
            return $this->filesystem->move(self::buildPath($path, $old_filename), self::buildPath($path, $new_filename));
        }

        return false;
    }

    /**
     * Build path.
     *
     * @param   array   $parts
     * @param   string  $filename
     * @param   string  $separator
     * @return  string
     */
    public static function buildPath($parts = [], $filename = null, $separator = DIRECTORY_SEPARATOR)
    {
        return (is_array($parts) ? implode($separator, $parts) : $parts) . ($filename ? $separator . $filename : '');
    }

    /**
     * Build disk path.
     *
     * @param   array   $parts
     * @param   string  $filename
     * @param   string  $separator
     * @return  string
     */
    private function buildDiskPath($parts = [], $filename = null, $separator = DIRECTORY_SEPARATOR)
    {
        return app()['config']["filesystems.disks.{$this->disk}.root"] . $separator . self::buildPath($parts, $filename);
    }

    /**
     * File exists.
     *
     * @param   string  $path
     * @param   string  $filename
     * @return  boolean
     */
    public function fileExists($path, $filename)
    {
        return $this->filesystem->exists(self::buildPath($path, $filename));
    }

    /**
     * Generate a unique filename.
     *
     * @param   string  $filename
     * @return  boolean
     */
    public static function generateUniqueFilename($filename)
    {
        return md5($filename . microtime());
    }

    /**
     * Updates all the image sizes in the given path.
     *
     * @param   string  $image
     * @param   string  $path
     * @param   array   $sizes
     * @param   boolean $detach
     * @return  array|mixed|string
     */
    public function uploadImage($image, $path, $sizes = [], $detach = true)
    {
        if ($detach) {
            $this->filesystem->deleteDirectory(self::buildPath($path));
        }

        $image_helper = new ImageHelper(self::buildDiskPath($path));
        $images = $image_helper->encode($image, $sizes);

        if (empty($sizes)) {
            $this->uploadFileToCloud(self::buildDiskPath($path, $images['filename']), $images['image']);
            $images = $images['filename'];
        }else{
            foreach ($images as $size => &$image) {
                $this->uploadFileToCloud(self::buildDiskPath($path, $image['filename']), $image['image']);
                $image = $image['filename'];
            }
        }

        return $images;
    }

    /**
     * Updates all the image sizes in the cloud container
     *
     * @param   string  $path
     * @param   mixed   $file
     * @throws  AppException
     * @return  void
     */
    private function uploadFileToCloud($path, $file)
    {
        $status = $this->filesystem->put($path, $file);

        if (!$status) {
            throw new AppException(AppExceptionType::$CLOUD_UPLOAD_ERROR);
        }
    }
}