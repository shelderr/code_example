<?php

namespace App\Models;

use App\Models\Events\Event;
use App\Models\Helpers\MultiSizeImageAccessor;
use App\Models\Helpers\Venue\VenueInterface;
use App\Traits\ElasticSearch\Searchable;
use App\Traits\Relations\FeedbackAble;
use App\Traits\IsBlockedScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Venue extends Model implements VenueInterface
{
    use HasFactory, FeedbackAble, IsBlockedScopeTrait, Searchable, MultiSizeImageAccessor;

    protected $fillable = [
        'name',
        'native_name',
        'alternative_names',
        'description',
        'image',
        'image_author',
        'image_source',
        'image_permission',
        'is_active',
        'country_id',
        'city',
        'street_address',
        'coordinates',
        'seating_capacity',
        'category_id',
        'opening_year',
        'opening_month',
        'opening_day',
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
        'name'              => 'string',
        'native_name'       => 'string',
        'alternative_names' => 'string',
        'description'       => 'string',
        'image'             => 'string',
        'image_author'      => 'string',
        'image_source'      => 'string',
        'is_active'         => 'boolean',
        'image_permission'  => 'string',
        'coordinates'       => 'string',
        'seating_capacity'  => 'string',
        'category_id'       => 'integer',
        'country_id'        => 'integer',
        'city'              => 'string',
        'street_address'    => 'string',
        'opening_year'      => 'integer',
        'opening_month'     => 'integer',
        'opening_day'       => 'integer',
        'wikipedia_url'     => 'string',
        'facebook_url'      => 'string',
        'youtube_url'       => 'string',
        'twitter_url'       => 'string',
        'instagram_url'     => 'string',
        'linkedin_url'      => 'string',
        'vk_url'            => 'string',
        'tiktok_url'        => 'string',
        'telegram_url'      => 'string',
        'web_url'           => 'string',
        'wikidata_url'      => 'string',
    ];

    protected $hidden = ['category_id'];

    protected $appends = ['multiSizeImages'];

    public static bool $withoutUrl = false;

    public const IMAGE_PERMISSION_ENUM = [
        self::IMAGE_PERMISSION_SOURCED,
        self::IMAGE_PERMISSION_OWNED,
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\HasOne // todo maybe to polimorphic  in future
    {
        return $this->hasOne(Category::class, 'id', 'category_id')
            ->where('type', '=', Category::VENUE_CATEGORY);
    }

    public function country(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function shows(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_venue')
            ->where('type', '=', Event::TYPE_SHOW)
            ->withTimestamps();
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_venue')
            ->where('type', '=', Event::TYPE_EVENT)
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function images(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Media::class, 'mediaable')
            ->where('type', '=', Media::TYPE_IMAGES);
    }

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
