<?php

namespace App\Models\Helpers\Event;

interface EventActInterface
{
    public const RELATION_PERSONS     = 'persons';
    public const RELATION_COLLECTIVES = 'collectives';
    public const RELATION_SHOWS       = 'shows';
    public const RELATION_AWARDS      = 'awards';
}
