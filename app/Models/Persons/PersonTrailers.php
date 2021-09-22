<?php

namespace App\Models\Persons;

use App\Models\Helpers\Event\TrailersInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonTrailers extends Model implements TrailersInterface
{
    use HasFactory;

    public const TYPES = [self::TYPE_YOUTUBE, self::TYPE_VIMEO];

    protected $fillable = [
        'person_id',
        'type',
        'url',
    ];

    protected $casts = [
        'person_id' => 'integer',
        'type'     => 'string',
        'url'      => 'string',
    ];
}
