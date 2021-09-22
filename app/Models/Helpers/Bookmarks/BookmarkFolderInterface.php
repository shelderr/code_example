<?php

namespace App\Models\Helpers\Bookmarks;

interface BookmarkFolderInterface
{
    //Relations
    public const RELATION_EVENTS      = 'events';
    public const RELATION_PERSONS     = 'persons';
    public const RELATION_COLLECTIVES = 'collectives';
    public const RELATION_VENUES      = 'venues';
}
