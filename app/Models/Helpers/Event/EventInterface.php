<?php

namespace App\Models\Helpers\Event;

interface EventInterface
{
    //TYPES
    public const TYPE_EVENT = 'event';
    public const TYPE_SHOW  = 'show';

    //Datetime
    public const MAX_SUB_YEAR = 29;
    public const MIN_YEAR     = 1900;

    //Folders
    public const POSTERS_FOLDER = '/uploads/events/posters/';
    public const MEDIA_FOLDER   = '/uploads/events/media/';
    public const ACTS_FOLDER    = '/uploads/events/acts/';
    public const SEASONS_FOLDER = '/uploads/events/seasons/';

    //Posters enums
    public const POSTER_PERMISSION_OWNED   = 'owned';
    public const POSTER_PERMISSION_SOURCED = 'sourced';

    //Relations
    public const RELATION_TRAILERS            = 'trailers';
    public const RELATION_POSTERS             = 'posters';
    public const RELATION_IMAGES              = 'images';
    public const RELATION_VIDEOS              = 'videos';
    public const RELATION_COUNTRIES_CREATED   = 'countriesCreated';
    public const RELATION_COUNTRIES_PRESENTED = 'countriesPresented';
    public const RELATION_LANGUAGES           = 'languages';
    public const RELATION_PRODUCTION_TYPE     = 'productionTypes';
    public const RELATION_SHOW_AUDIENCE       = 'showAudience';
    public const RELATION_SHOW_TYPES          = 'showTypes';
    public const RELATION_EVENT_TYPES         = 'eventTypes';
    public const RELATION_CREATORS            = 'creators';
    public const RELATION_FUTURE_CREATORS     = 'futureCreators';
    public const RELATION_CASTS               = 'casts';
    public const RELATION_FUTURE_CASTS        = 'futureCasts';
    public const RELATION_CREW                = 'crew';
    public const RELATION_FUTURE_CREW         = 'futureCrew';
    public const RELATION_ACTS                = 'acts';
    public const RELATION_JURY                = 'jury';
    public const RELATION_FUTURE_JURY         = 'futureJury';
    public const RELATION_VENUE               = 'venue';
    public const RELATION_ACTIVE_VENUE        = 'activeVenues';
    public const RELATION_EDITIONS            = 'editions';

    //Fields
    public const POSTER_WIDTH  = 1000;
    public const POSTER_HEIGHT = 1440;

    //REGEX
    public const ALPHABETICAL_SORTING_REGEX = '/^[A-Z]+$/';
    public const MAX_COUNTRIES_NUMBER       = 100;

    //Show categories types
    public const SHOW_TYPE_CATEGORY       = 'show_type';
    public const EVENT_TYPE_CATEGORY      = 'event_type';
    public const PRODUCTION_TYPE_CATEGORY = 'production_type';
    public const SHOW_AUDIENCE_CATEGORY   = 'show_audience';
    public const SHOW_CATEGORY            = 'show';

    //Order enum
    public const ORDER_ASC  = 'asc';
    public const ORDER_DESC = 'desc';
}
