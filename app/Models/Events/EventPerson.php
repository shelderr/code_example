<?php

namespace App\Models\Events;

use App\Models\Collective;
use App\Models\Helpers\Event\EventPersonInterface;
use App\Models\Persons;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventPerson extends Model implements EventPersonInterface
{
    use HasFactory;

    protected $table = 'event_person';

    protected $with = ['roles', 'awards'];

    protected $fillable = [
        'event_id',
        'persons_id',
        'collective_id',
        'field_name',
        'is_future',
        'is_original',
    ];

    protected $casts = [
        'event_id'    => 'integer',
        'persons_id'  => 'integer',
        'field_name'  => 'string',
        'is_future'   => 'boolean',
        'is_original' => 'boolean',
    ];

    /** @var array Types than can be applied for event type */
    public static array $eventTypes = [self::RELATION_CREW, self::RELATION_JURY];

    /** @var array Types than can be applied for show type */
    public static array $showTypes = [self::RELATION_CREATORS, self::RELATION_CASTS, self::RELATION_CREW];

    /** @var array All resolved types  */
    public static array $allTypes = [
        self::RELATION_CREW,
        self::RELATION_JURY,
        self::RELATION_CREATORS,
        self::RELATION_CASTS,
    ];

    protected $hidden = ['event_id', 'persons_id', 'field_name', 'collective_id'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id')->whereType(Event::TYPE_EVENT);
    }

    public function show(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id')->whereType(Event::TYPE_SHOW);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(EventPersonRole::class, 'event_person_id');
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

    public function person(): BelongsTo
    {
        return $this->belongsTo(Persons::class, 'persons_id', 'id');
    }

    public function collective(): BelongsTo
    {
        return $this->belongsTo(Collective::class, 'collective_id', 'id');
    }
}
