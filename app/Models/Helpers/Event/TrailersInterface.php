<?php

namespace App\Models\Helpers\Event;

interface TrailersInterface
{
    public const MAX_TRAILERS_COUNT = 1;

    //Types
    public const TYPE_YOUTUBE = 'youtube';
    public const TYPE_VIMEO   = 'vimeo';

    //Regex
    public const YOUTUBE_REGEX = '/^(https?\:\/\/)?(www\.youtube\.com|youtu\.?be)\/.+$/';
    public const VIMEO_REGEX   = '/(?:http|https)?:?\/?\/?(?:www\.|player\.)?vimeo\.com\/(videos\/|video\/|)(\d+)(?:|\/\?)/';
}
