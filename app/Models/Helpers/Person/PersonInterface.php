<?php

namespace App\Models\Helpers\Person;

interface PersonInterface
{
    // Photo params
    public const PHOTO_WIDTH  = 1000;
    public const PHOTO_HEIGHT = 1440;
    public const PHOTO_FOLDER = '/uploads/persons/photo/';
    public const MEDIA_FOLDER = '/uploads/persons/media/';

    //Dates
    public const DATETIME_FORMAT = 'Y-m-d H:i';
    public const DATE_FORMAT     = 'Y-m-d';

    //Regex
    public const ALPHABETICAL_SORTING_REGEX = '/^[A-Z]+$/';

    //allows letters on all languages and only one dot
    public const NAME_REGEX = '/^([\p{L}])([^\.]|([^\.])\.)+$/u';

    //Enums
    public const ORDER_ASC  = 'asc';
    public const ORDER_DESC = 'desc';

    //Image enum
    //Posters enums
    public const POSTER_PERMISSION_OWNED   = 'owned';
    public const POSTER_PERMISSION_SOURCED = 'sourced';

    //Relations
    public const COUNTRY_RELATION = 'countries';
    public const ROLES_RELATION   = 'roles';
    public const SHOWS_RELATIONS  = 'shows';
    public const LINKED_USER      = 'linkedUser';

    //Country
    public const MAX_COUNTRIES_NUMBER = 100;
}
