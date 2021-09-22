<?php

namespace App\Models\Collectives;

use App\Models\Helpers\Event\TrailersInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectiveTrailers extends Model implements TrailersInterface
{
    use HasFactory;

    public const TYPES = [self::TYPE_YOUTUBE, self::TYPE_VIMEO];

    protected $fillable = [
        'сollective_id',
        'type',
        'url',
    ];

    protected $casts = [
        'сollective_id' => 'integer',
        'type'          => 'string',
        'url'           => 'string',
    ];
}
