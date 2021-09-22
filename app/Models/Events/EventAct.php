<?php

namespace App\Models\Events;

use App\Models\Collective;
use App\Models\Helpers\Event\EventActInterface;
use App\Models\Persons;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class EventAct extends Model implements EventActInterface
{
    use HasFactory;

    public const RELATIONS = [
        self::RELATION_PERSONS,
        self::RELATION_COLLECTIVES,
        self::RELATION_SHOWS,
        self::RELATION_AWARDS,
    ];

    protected $fillable = [
        'title',
        'event_id',
        'description',
        'image',
        'image_author',
        'image_source',
        'image_permission',
        'link',
        'is_future',
    ];

    protected $casts = [
        'title'            => 'string',
        'event_id'         => 'integer',
        'description'      => 'string',
        'image'            => 'string',
        'image_author'     => 'string',
        'image_source'     => 'string',
        'image_permission' => 'string',
        'link'             => 'string',
        'is_future'        => 'boolean',
    ];

    protected $hidden = ['event_id'];

    protected $with = self::RELATIONS;

    public static bool $withoutUrl = false;

    public function persons(): MorphToMany
    {
        return $this->morphedByMany(
            Persons::class,
            'event_actable',
            'event_actables',
        );
    }

    public function collectives(): MorphToMany
    {
        return $this->morphedByMany(
            Collective::class,
            'event_actable',
            'event_actables',
        );
    }

    public function shows(): MorphToMany
    {
        return $this->morphedByMany(
            Event::class,
            'event_actable',
            'event_actables',
        );
    }

    /**
     * Awards that attempted for person of this event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function awards(): BelongsToMany
    {
        return $this->morphToMany(Awards::class, 'awardsable', 'awardsable');
    }

    public function getImageAttribute($file)
    {
        if (is_null($file) || self::$withoutUrl === true) {
            return $file;
        }

        return config('app.domain') . '/storage' . $file;
    }
}
