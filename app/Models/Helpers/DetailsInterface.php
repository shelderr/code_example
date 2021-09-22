<?php

namespace App\Models\Helpers;

interface DetailsInterface
{
    //Entities
    public const PERSONS_ENTITY    = 'persons';
    public const EVENTS_ENTITY      = 'events';
    public const SHOWS_ENTITY      = 'shows';
    public const COLLECTIVES_ENTITY = 'collectives';

    //images enums
    public const IMAGE_PERMISSION_OWNED   = 'owned';
    public const IMAGE_PERMISSION_SOURCED = 'sourced';

    //Folders
    public const IMAGE_FOLDER = '/uploads/details/photo/';
}
