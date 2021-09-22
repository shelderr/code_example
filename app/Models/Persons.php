<?php

namespace App\Models;

use App\Models\Events\Event;
use App\Models\Events\EventPerson;
use App\Models\Helpers\MultiSizeImageAccessor;
use App\Models\Helpers\Person\PersonInterface;
use App\Models\Persons\PersonTrailers;
use App\Traits\ElasticSearch\Searchable;
use App\Traits\Relations\FeedbackAble;
use App\Traits\IsBlockedScopeTrait;
use App\Traits\Relations\Detailsable;
use App\Traits\Relations\Linksable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persons extends Model implements PersonInterface
{
    use HasFactory,
        SoftDeletes,
        IsBlockedScopeTrait,
        Searchable,
        MultiSizeImageAccessor;

    //Relations
    use Detailsable, FeedbackAble, Linksable;

    protected $table = 'persons';

    public static bool $withoutUrl = false;

    public static array $sortingEnums = [self::ORDER_ASC, self::ORDER_DESC];

    public static array $allRelations = [self::COUNTRY_RELATION, self::ROLES_RELATION];

    public const PERSONS_POSTER_PERMISSION_ENUMS = [
        self::POSTER_PERMISSION_OWNED,
        self::POSTER_PERMISSION_SOURCED,
    ];

    protected $fillable = [
        'name',
        'stage_name',
        'bio',
        'company',
        'job',
        'image',
        'image_author',
        'image_source',
        'image_permission',
        'is_deceased',
        'birth_year',
        'birth_month',
        'birth_day',
        'death_year',
        'death_month',
        'death_day',
        'birth_place',
        'wikipedia_url',
        'facebook_url',
        'youtube_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'vk_url',
        'tiktok_url',
        'telegram_url',
        'web_url',
        'wikidata_url',
    ];

    protected $casts = [
        'name'             => 'string',
        'stage_name'       => 'string',
        'bio'              => 'string',
        'company'          => 'string',
        'job'              => 'string',
        'image'            => 'string',
        'image_author'     => 'string',
        'image_source'     => 'string',
        'image_permission' => 'string',
        'is_deceased'      => 'boolean',
        'birth_place'      => 'string',
        'birth_year'       => 'integer',
        'birth_month'      => 'integer',
        'birth_day'        => 'integer',
        'death_year'       => 'integer',
        'death_month'      => 'integer',
        'death_day'        => 'integer',
        'wikipedia_url'    => 'string',
        'facebook_url'     => 'string',
        'youtube_url'      => 'string',
        'twitter_url'      => 'string',
        'instagram_url'    => 'string',
        'linkedin_url'     => 'string',
        'vk_url'           => 'string',
        'tiktok_url'       => 'string',
        'telegram_url'     => 'string',
        'web_url'          => 'string',
        'wikidata_url'     => 'string'
    ];

    protected $hidden = ['country_id', 'deleted_at', 'pivot'];

    protected $appends = ['is_member', 'multiSizeImages'];

    protected $with = [self::COUNTRY_RELATION];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function images(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Media::class, 'mediaable')
            ->where('type', '=', Media::TYPE_IMAGES);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function headshots(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Media::class, 'mediaable')
            ->where('type', '=', Media::TYPE_HEADSHOTS);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function videos(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Media::class, 'mediaable')
            ->where('type', '=', Media::TYPE_VIDEOS);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function countries(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Country::class, 'countryable', 'countryable')
            ->where('country_type', '=', Country::PERSON_COUNTRY_TYPE)
            ->withTimestamps();
    }

    /**
     * Relation to linked user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function linkedUser(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_person', 'person_id')->withTimestamps();
    }

    /**
     * Get link to user status
     *
     * @param string $status
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getLinkedUserByStatus(string $status): BelongsToMany
    {
        return $this->linkedUser()->where('status', '=', $status);
    }

    /**
     * Check if person is verified
     *
     * @return bool
     */
    public function getIsMemberAttribute(): bool
    {
        return $this->getLinkedUserByStatus(User::LINK_STATUS_ACCEPTED)->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Roles::class, 'person_role')->with('parent')->withTimestamps();
    }

    /**
     * Events where person is creator
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creator()
    {
        return $this->hasMany(EventPerson::class, 'persons_id', 'id')
            ->where('field_name', '=', Event::RELATION_CREATORS)
            ->with(Event::ALL_EVENTS);
    }

    /**
     * Events where person is in cast
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cast()
    {
        return $this->hasMany(EventPerson::class, 'persons_id', 'id')
            ->where('field_name', '=', Event::RELATION_CASTS)
            ->with(Event::ALL_EVENTS);
    }

    /**
     * Events where person is in crew
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function crew()
    {
        return $this->hasMany(EventPerson::class, 'persons_id', 'id')
            ->where('field_name', '=', Event::RELATION_CREW)
            ->with(Event::ALL_EVENTS);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trailers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PersonTrailers::class, 'person_id')
            ->orderBy('created_at', 'desc');
    }
    /**
     * @param $file
     *
     * @return string
     */
    public function getImageAttribute($file)
    {
        if (is_null($file) || self::$withoutUrl === true) {
            return $file;
        }

        return config('app.domain') . '/storage' . $file;
    }

    public function imageFieldName(): string
    {
        return 'image';
    }

    /**
     * Displays rows with images on top of the list
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeImagesFirstly($query): mixed
    {
        return $query->orderByRaw('image IS NOT NULL desc, created_at desc');
    }
}
