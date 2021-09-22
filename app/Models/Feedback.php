<?php

namespace App\Models;

use App\Models\Events\Event;
use App\Models\Feedback\FeedbackLinks;
use App\Models\Helpers\FeedbackInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model implements FeedbackInterface
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'status',
        'subject',
        'message',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'type'    => 'string',
        'status'  => 'string',
        'subject' => 'string',
        'message' => 'string',
    ];

    public static array $types = [
        self::TYPE_OTHER,
        self::TYPE_BUG,
        self::TYPE_BULK,
        self::TYPE_COPYRIGHT,
        self::TYPE_IDEAS,
        self::TYPE_INAPPROPRIATE_CONTENT,
        self::TYPE_MISTAKE,
    ];

    public static array $statuses = [
        self::STATUS_DECLINED,
        self::STATUS_PENDING,
        self::STATUS_RESOLVED,
    ];

    public static array $entityTypes = [
        self::EVENT_ENTITY,
        self::SHOW_ENTITY,
        self::COLLECTIVE_ENTITY,
        self::VENUE_ENTITY,
        self::PERSON_ENTITY,
    ];

    protected $appends = ['entity'];

    protected $with = [self::RELATION_IMAGES];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function images(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Media::class, 'mediaable')
            ->where('type', '=', Media::TYPE_FEEDBACK_IMAGE);
    }

    public function getEntityAttribute(): array
    {
        $relation = [];

        if ($this->person()->exists()) {
            $relation = ['person' => $this->person()->get()];
        }

        if ($this->venue()->exists()) {
            $relation = ['venue' => $this->venue()->get()];
        }

        if ($this->event()->exists()) {
            $relation = ['event' => $this->event()->get()];
        }

        if ($this->show()->exists()) {
            $relation = ['show' => $this->show()->get()];
        }

        if ($this->collective()->exists()) {
            $relation = ['collective' => $this->collective()->get()];
        }

        return $relation;
    }

    public function person()
    {
        return $this->morphedByMany(Persons::class, 'feedbackables');
    }

    public function venue()
    {
        return $this->morphedByMany(Venue::class, 'feedbackables');
    }

    public function event()
    {
        return $this->morphedByMany(Event::class, 'feedbackables')
            ->where('type', '=', Event::TYPE_EVENT);
    }

    public function show()
    {
        return $this->morphedByMany(Event::class, 'feedbackables')
            ->where('type', '=', Event::TYPE_SHOW);
    }

    public function collective()
    {
        return $this->morphedByMany(Collective::class, 'feedbackables');
    }

    public function links()
    {
        return $this->hasMany(FeedbackLinks::class, 'feedback_id', 'id');
    }
}
