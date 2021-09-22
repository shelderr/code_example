<?php

namespace App\Models\Helpers;

trait MultiSizeImageAccessor
{
    abstract public function imageFieldName(): string;

    /**
     * @return array
     */
    public function getMultiSizeImagesAttribute(): null|array
    {
        $field = $this->imageFieldName();
        $sizes = ['@tb', '@sm', '@lg'];

        $imageStoragePath = preg_split('@(?=uploads/)@', $this->$field);
        $image            = explode('.', array_pop($imageStoragePath));
        $filename          = $image[0] ?? null;
        $fileExt           = $image[1] ?? null;
        $images           = [];

        foreach ($sizes as $size) {
            $images[] = "$filename$size.$fileExt";
        }

        if (is_null($this->$field)) {
            return null;
        }

        if (self::$withoutUrl === true) {
            return $images;
        }

        $path = config('app.domain') . '/storage/';

        return preg_filter('/^/', $path, $images);
    }
}
