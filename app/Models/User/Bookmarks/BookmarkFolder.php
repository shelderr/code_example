<?php

namespace App\Models\User\Bookmarks;

use App\Models\Collective;
use App\Models\Events\Event;
use App\Models\Helpers\Bookmarks\BookmarkFolderInterface;
use App\Models\Persons;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookmarkFolder extends Model implements BookmarkFolderInterface
{
    use HasFactory;

    public const ALL_RELATIONS = [self::RELATION_EVENTS];

    protected $table = 'bookmark_folder';

    protected $fillable = [
        'user_id',
        'name',
    ];

    protected $hidden = ['user_id'];

    protected $appends = ['items_count'];

    public static array $allTypes = [
        self::RELATION_EVENTS,
        self::RELATION_PERSONS,
        self::RELATION_VENUES,
        self::RELATION_COLLECTIVES,
    ];

    public static array $relationsEntity = [
        self::RELATION_EVENTS      => Event::class,
        self::RELATION_PERSONS     => Persons::class,
        self::RELATION_VENUES      => Venue::class,
        self::RELATION_COLLECTIVES => Collective::class,
    ];

    /**
     * Counting all appended bookmarks to the folder
     *
     * @return int
     */
    public function getItemsCountAttribute(): int
    {
        return Folderable::where('bookmark_folder_id', '=', $this->id)->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function events()
    {
        return $this->morphedByMany(Event::class, 'folderable', 'folderable')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function persons()
    {
        return $this->morphedByMany(Persons::class, 'folderable', 'folderable')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function collectives()
    {
        return $this->morphedByMany(Collective::class, 'folderable', 'folderable')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function venues()
    {
        return $this->morphedByMany(Venue::class, 'folderable', 'folderable')->withTimestamps();
    }
}
