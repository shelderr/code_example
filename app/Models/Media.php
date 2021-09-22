<?php

namespace App\Models;

use App\Models\Events\Event;
use App\Models\Helpers\MediaInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model implements MediaInterface
{
    use HasFactory;

    public const ALL_PERMISSIONS = [self::POSTER_PERMISSION_SOURCED, self::POSTER_PERMISSION_OWNED];

    public const ALL_TYPES = [self::TYPE_HEADSHOTS, self::TYPE_IMAGES, self::TYPE_POSTERS, self::TYPE_VIDEOS];

    protected $fillable = [
        'type',
        'url',
        'author',
        'source',
        'permission',
    ];

    protected $hidden = ['pivot'];

    protected $appends = ['entity'];

    /**
     * @var bool - value for getUrlAttribute()
     */
    public static bool $withoutUrl = false;


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
        return $this->morphedByMany(Persons::class, 'mediaable');
    }

    public function venue()
    {
        return $this->morphedByMany(Venue::class, 'mediaable');
    }

    public function event()
    {
        return $this->morphedByMany(Event::class, 'mediaable')
            ->where('type', '=', Event::TYPE_EVENT);
    }

    public function show()
    {
        return $this->morphedByMany(Event::class, 'mediaable')
            ->where('type', '=', Event::TYPE_SHOW);
    }

    public function collective()
    {
        return $this->morphedByMany(Collective::class, 'mediaable');
    }
    /**
     * @param $file
     *
     * @return string
     */
    public function getUrlAttribute($file)
    {
        if (is_null($file) || self::$withoutUrl === true || $this->type == self::TYPE_VIDEOS) {
            return $file;
        }

        return config('app.domain') . '/storage' . $file;
    }
}
