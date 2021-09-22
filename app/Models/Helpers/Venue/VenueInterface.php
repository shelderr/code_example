<?php

namespace App\Models\Helpers\Venue;

interface VenueInterface
{
    public const MAX_COUNTRY_NUMBER = 100;

    //Datetime
    public const MAX_SUB_YEAR = 29;
    public const MIN_YEAR = 1900;

    //Folders
    public const IMAGE_FOLDER = '/uploads/venue/images/';
    public const MEDIA_FOLDER = '/uploads/venue/media/';

    //Posters enums
    public const IMAGE_PERMISSION_OWNED   = 'owned';
    public const IMAGE_PERMISSION_SOURCED = 'sourced';

    //Relations
    public const CATEGORY_RELATION = 'category';

    //REGEX
    public const ALPHABETICAL_SORTING_REGEX = '/^[A-Z]+$/';
}
