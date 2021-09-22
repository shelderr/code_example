<?php

namespace App\Models\Events;

use App\Models\Helpers\Event\TrailersInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTrailers extends Model implements TrailersInterface
{
    use HasFactory;

    public const TYPES = [self::TYPE_YOUTUBE, self::TYPE_VIMEO];

    protected $fillable = [
        'event_id',
        'type',
        'url',
    ];

    protected $casts = [
        'event_id' => 'integer',
        'type'     => 'string',
        'url'      => 'string',
    ];
}
