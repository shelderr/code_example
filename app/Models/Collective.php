<?php

namespace App\Models;

use App\Models\Collectives\CollectivePerson;
use App\Models\Collectives\CollectiveTrailers;
use App\Models\Events\EventPerson;
use App\Models\Helpers\Collective\CollectiveInterface;
use App\Models\Helpers\MultiSizeImageAccessor;
use App\Traits\ElasticSearch\Searchable;
use App\Traits\Relations\Criticsable;
use App\Traits\Relations\Detailsable;
use App\Traits\Relations\FeedbackAble;
use App\Traits\IsBlockedScopeTrait;
use App\Traits\Relations\Linksable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Testing\Fluent\Concerns\Has;

class Collective extends Model implements CollectiveInterface
{
    use HasFactory, IsBlockedScopeTrait, Searchable, MultiSizeImageAccessor;

    use FeedbackAble, Detailsable, Linksable;

    public static bool $withoutUrl = false;

    public const IMAGE_PERMISSION_ENUM = [
        self::IMAGE_PERMISSION_OWNED,
        self::IMAGE_PERMISSION_SOURCED,
    ];

    public const ALL_RELATIONS = [
        self::CATEGORY_RELATION,
        self::COUNTRIES_RELATION,
        // self::PERSONS_RELATION,
    ];

    public static array $sortDir = [self::ORDER_ASC, self::ORDER_DESC];

    protected $fillable = [
        'name',
        'image',
        'image_author',
        'image_source',
        'image_permission',
        'other_name',
        'bio',
        'category_id',
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
        'image'            => 'string',
        'image_author'     => 'string',
        'image_source'     => 'string',
        'image_permission' => 'string',
        'other_name'       => 'string',
        'bio'              => 'string',
        'category_id'      => 'integer',
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
        'wikidata_url'     => 'string',
    ];

    protected $appends = [
        'entity_type',
        'multiSizeImages',
    ];

    protected $hidden = ['category_id'];

    public function category(): \Illuminate\Database\Eloquent\Relations\HasOne // todo maybe to polimorphic  in future
    {
        return $this->hasOne(Category::class, 'id', 'category_id')
            ->where('type', '=', Category::COLLECTIVE_CATEGORY);
    }

    public function persons(): HasMany
    {
        return $this->hasMany(CollectivePerson::class, 'collective_id')->with('person');
    }

    public function events(): HasMany
    {
        return $this->hasMany(EventPerson::class, 'collective_id', 'id')
            ->whereNotNull('event_id')
            ->whereDoesntHave('show')
            ->with('event');
    }

    public function shows(): HasMany
    {
        return $this->hasMany(EventPerson::class, 'collective_id', 'id')
            ->whereNotNull('event_id')
            ->whereDoesntHave('event')
            ->with('show');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function countries(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Country::class, 'countryable', 'countryable')
            ->where('country_type', '=', Country::COLLECTIVE_COUNTRY_TYPE)
            ->withTimestamps();
    }

    public function getImageAttribute($file)
    {
        if (is_null($file) || self::$withoutUrl === true) {
            return $file;
        }

        return config('app.domain') . '/storage' . $file;
    }

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trailers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CollectiveTrailers::class, 'collective_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * @param $file
     *
     * @return string
     */
    public function getEntityTypeAttribute()
    {
        return 'collective';
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
