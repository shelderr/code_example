<?php

namespace App\Models\Helpers\Event;

interface EventPersonInterface
{
    public const RELATION_JURY     = 'jury';
    public const RELATION_CREATORS = 'creators';
    public const RELATION_CASTS    = 'casts';
    public const RELATION_CREW     = 'crew';
}
