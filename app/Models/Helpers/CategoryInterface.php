<?php

namespace App\Models\Helpers;

/**
 * Interface Google2FAInterface
 *
 * @package App\Models\Helpers
 */
interface CategoryInterface
{
    public const SHOW_AUDIENCE_TYPE  = 'show_audience';
    public const PRODUCTION_TYPE     = 'production_type';
    public const SHOW_TYPE           = 'show_type';
    public const EVENT_TYPE          = 'event_type';
    public const COLLECTIVE_CATEGORY = 'collectives_categories';
    public const VENUE_CATEGORY      = 'venue_category';
}
