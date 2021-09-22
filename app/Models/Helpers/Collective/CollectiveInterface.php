<?php

namespace App\Models\Helpers\Collective;

interface CollectiveInterface
{
    public const MAX_COUNTRY_NUMBER = 100;

    //Datetime
    public const MAX_SUB_YEAR = 29;
    public const MIN_YEAR = 1900;

    //Folders
    public const IMAGE_FOLDER = '/uploads/collectives/images/';
    public const MEDIA_FOLDER   = '/uploads/collectives/media/';

    //Posters enums
    public const IMAGE_PERMISSION_OWNED   = 'owned';
    public const IMAGE_PERMISSION_SOURCED = 'sourced';

    //Relations
    public const CATEGORY_RELATION  = 'category';
    public const COUNTRIES_RELATION = 'countries';
    public const PERSONS_RELATION   = 'persons';

    //REGEX
    public const ALPHABETICAL_SORTING_REGEX = '/^[A-Z]+$/';

    //Categories types
    public const CATEGORIES = 'collectives_categories';

    //Order enum
    public const ORDER_ASC  = 'asc';
    public const ORDER_DESC = 'desc';
}
