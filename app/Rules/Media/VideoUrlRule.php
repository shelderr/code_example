<?php

namespace App\Rules\Media;

use App\Models\Media;
use Illuminate\Contracts\Validation\Rule;

class VideoUrlRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param        $url
     *
     * @return bool
     */
    public function passes($attribute, $url)
    {
        $youtubeCheck = preg_match(Media::YOUTUBE_REGEX, $url);
        $vimeoCheck   = preg_match(Media::VIMEO_REGEX, $url);

        if ($youtubeCheck == false  && $vimeoCheck == false) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid url';
    }
}
