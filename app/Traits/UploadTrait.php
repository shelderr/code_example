<?php

namespace App\Traits;

use Guizoxxv\LaravelMultiSizeImage\MultiSizeImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait UploadTrait
{
    /**
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     * @param null                          $folder
     * @param string                        $disk
     * @param null                          $filename
     *
     * @return false|string
     */
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name         = ! is_null($filename) ? $filename : Str::random(25);
        $explodedName = explode('.', $name);
        $name         = array_shift($explodedName);

        return $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), $disk);
    }

    /**
     * Upload image in 3 different sizes
     *
     * @link https://github.com/guizoxxv/laravel-multi-size-image
     * @throws \Exception
     */
    public function uploadMultipleSizes(
        UploadedFile $uploadedFile,
        $folder = null,
        $disk = 'public',
        $filename = null
    ): array {
        $name         = ! is_null($filename) ? $filename : Str::random(25);
        $explodedName = explode('.', $name);
        $name         = array_shift($explodedName);

        $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), $disk);

        $imagePath      = \Storage::path('public') . $folder . $filename;
        $multiSizeImage = new MultiSizeImage();

        return $multiSizeImage->processImage($imagePath);
    }

    /**
     * Function gets file from storage and transform it to \Illuminate\Http\UploadedFile
     *
     * @param      $path
     * @param bool $test
     *
     * @return \Illuminate\Http\UploadedFile
     */
    public function getUploadedFileFromStorage($path, $test = false): UploadedFile
    {
        $name         = File::name($path);
        $extension    = File::extension($path);
        $originalName = $name . '.' . $extension;
        $mimeType     = File::mimeType($path);
        $size         = File::size($path);

        return new UploadedFile($path, $originalName, $mimeType, $size, $test);
    }
}
