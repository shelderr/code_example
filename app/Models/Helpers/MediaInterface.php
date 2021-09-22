<?php

namespace App\Models\Helpers;

interface MediaInterface
{
    //TYPES
    public const TYPE_POSTERS        = 'poster';
    public const TYPE_VIDEOS         = 'video';
    public const TYPE_IMAGES         = 'image';
    public const TYPE_HEADSHOTS      = 'headshot';
    public const TYPE_FEEDBACK_IMAGE = 'feedback_image';

    //Regexes
    public const YOUTUBE_REGEX = '/^(https?\:\/\/)?(www\.youtube\.com|youtu\.?be)\/.+$/';
    public const VIMEO_REGEX   = '/(?:http|https)?:?\/?\/?(?:www\.|player\.)?vimeo\.com\/(videos\/|video\/|)(\d+)(?:|\/\?)/';

    //Posters enums
    public const POSTER_PERMISSION_OWNED   = 'owned';
    public const POSTER_PERMISSION_SOURCED = 'sourced';
}
