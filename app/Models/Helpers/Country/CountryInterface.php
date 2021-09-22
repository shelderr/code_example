<?php

namespace App\Models\Helpers\Country;

interface CountryInterface
{
    //EVENTS ENUMS
    public const EVENT_COUNTRY_PRESENTED_TYPE = 'event_country_presented';
    public const EVENT_COUNTRY_CREATED_TYPE   = 'event_country_created';

    //PERSONS ENUMS
    public const PERSON_COUNTRY_TYPE = 'person_country_type';

    //COLLECTIVES ENUM
    public const COLLECTIVE_COUNTRY_TYPE = 'collective_country_type';
}
