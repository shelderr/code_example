<?php

namespace App\Models\Events;

use App\Models\Category;
use App\Models\Country;
use App\Models\Helpers\Event\EventInterface;
use App\Models\Helpers\MultiSizeImageAccessor;
use App\Models\Language;
use App\Models\Media;
use App\Models\Venue;
use App\Services\Base\BaseAppGuards;
use App\Traits\ElasticSearch\Searchable;
use App\Traits\Relations\Criticsable;
use App\Traits\Relations\Detailsable;
use App\Traits\Relations\FeedbackAble;
use App\Traits\IsBlockedScopeTrait;
use App\Traits\Relations\Linksable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Auth;

class Event extends Model implements EventInterface
{
    use HasFactory, IsBlockedScopeTrait, Searchable, MultiSizeImageAccessor;

    use FeedbackAble, Detailsable, Linksable, Criticsable;

    public const ALL_EVENTS = [
        self::TYPE_EVENT,
        self::TYPE_SHOW,
    ];

    public const ALL_RELATIONS = [
        self::RELATION_LANGUAGES,
        self::RELATION_PRODUCTION_TYPE,
        self::RELATION_SHOW_AUDIENCE,
        self::RELATION_SHOW_TYPES,
        self::RELATION_EVENT_TYPES,
        self::RELATION_COUNTRIES_CREATED,
        self::RELATION_COUNTRIES_PRESENTED,
        self::RELATION_TRAILERS,
        self::RELATION_ACTS,
    ];

    public const ALL_PERSONS = [
        self::RELATION_CREATORS,
        self::RELATION_FUTURE_CREATORS,
        self::RELATION_CREW,
        self::RELATION_FUTURE_CREW,
        self::RELATION_CASTS,
        self::RELATION_FUTURE_CASTS,
    ];

    public const EVENT_POSTER_PERMISSION_ENUMS = [
        self::POSTER_PERMISSION_OWNED,
        self::POSTER_PERMISSION_SOURCED,
    ];

    public static array $sortDir = [self::ORDER_ASC, self::ORDER_DESC];

    public static bool $withoutUrl = false;

    protected $fillable = [
        'title',
        'title_original_lang',
        'slogan',
        'type',
        'is_active',
        'is_original',
        'poster',
        'poster_author',
        'poster_source',
        'poster_permission',
        'description',
        'company_name',
        'tickets_url',
        'start_year',
        'start_month',
        'start_day',
        'end_year',
        'end_month',
        'end_day',
        'production_type_id',
        'show_audience_id',
        'show_type_id',
        'year_established',
        'is_television',
        'established_year',
        'established_month',
        'established_day',
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
        'is_original',
        'city',
        'wikidata_url',
    ];

    protected $casts = [
        'title'               => 'string',
        'title_original_lang' => 'string',
        'slogan'              => 'string',
        'type'                => 'string',
        'is_active'           => 'boolean',
        'is_original'         => 'boolean',
        'poster'              => 'string',
        'poster_author'       => 'string',
        'poster_source'       => 'string',
        'poster_permission'   => 'string',
        'description'         => 'string',
        'tickets_url'         => 'string',
        'start_date'          => 'date',
        'company_name'        => 'string',
        'end_date'            => 'date',
        'producer_id'         => 'integer',
        'president_id'        => 'integer',
        'parent_id'           => 'integer',
        'year_established'    => 'date',
        'is_television'       => 'boolean',
        'established_year'    => 'integer',
        'established_month'   => 'integer',
        'established_day'     => 'integer',
        'opening_year'        => 'integer',
        'opening_month'       => 'integer',
        'opening_day'         => 'integer',
        'end_year'            => 'integer',
        'end_month'           => 'integer',
        'end_day'             => 'integer',
        'production_type_id'  => 'integer',
        'show_audience_id'    => 'integer',
        'show_type_id'        => 'integer',
        'wikipedia_url'       => 'string',
        'facebook_url'        => 'string',
        'youtube_url'         => 'string',
        'twitter_url'         => 'string',
        'instagram_url'       => 'string',
        'linkedin_url'        => 'string',
        'vk_url'              => 'string',
        'tiktok_url'          => 'string',
        'telegram_url'        => 'string',
        'web_url'             => 'string',
        'city'                => 'string',
        'wikidata_url'        => 'string',
    ];

    protected $hidden = ['parent_id', 'production_type_id', 'show_audience_id'];

    protected $appends = [
        'rating',
        'userRating',
        'multiSizeImages',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function countriesCreated(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Country::class, 'countryable', 'countryable')
            ->where('country_type', '=', Country::EVENT_COUNTRY_CREATED_TYPE)
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function countriesPresented(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Country::class, 'countryable', 'countryable')
            ->where('country_type', '=', Country::EVENT_COUNTRY_PRESENTED_TYPE)
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function productionTypes(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoriesable', 'categoriesables')
            ->where('type', '=', Category::PRODUCTION_TYPE);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function showAudience(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        // todo maybe to polimorphic  in future
        return $this->hasOne(Category::class, 'id', 'show_audience_id')
            ->where('type', '=', Category::SHOW_AUDIENCE_TYPE);
    }

    public function showTypes(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoriesable', 'categoriesables')
            ->where('type', '=', Category::SHOW_TYPE);
    }

    public function eventTypes(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoriesable', 'categoriesables')
            ->where('type', '=', Category::EVENT_TYPE);;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function languages(): MorphToMany
    {
        return $this->morphToMany(Language::class, 'languageable', 'languageables');
    }

    /**
     * Users applauds for current event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applauds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventApplauds::class, 'event_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trailers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventTrailers::class, 'event_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function posters(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Media::class, 'mediaable')
            ->where('type', '=', Media::TYPE_POSTERS);
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
    public function videos(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Media::class, 'mediaable')
            ->where('type', '=', Media::TYPE_VIDEOS);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creators(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventPerson::class, 'event_id')
            ->where('field_name', '=', self::RELATION_CREATORS)
            ->where('is_future', '=', false)
            ->with('person');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function futureCreators(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventPerson::class, 'event_id')
            ->where('field_name', '=', self::RELATION_CREATORS)
            ->where('is_future', '=', true)
            ->with('person');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jury(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventPerson::class, 'event_id')
            ->where('field_name', '=', self::RELATION_JURY)
            ->where('is_future', '=', false)
            ->with('person')
            ->with('collective');
    }

    public function futureJury(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventPerson::class, 'event_id')
            ->where('field_name', '=', self::RELATION_JURY)
            ->where('is_future', '=', true)
            ->with('person')
            ->with('collective');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function casts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventPerson::class, 'event_id')
            ->where('field_name', '=', self::RELATION_CASTS)
            ->where('is_future', '=', false)
            ->with('person')
            ->with('collective');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function futureCasts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventPerson::class, 'event_id')
            ->where('field_name', '=', self::RELATION_CASTS)
            ->where('is_future', '=', true)
            ->with('person')
            ->with('collective');
    }

    public function crew(): HasMany
    {
        return $this->hasMany(EventPerson::class, 'event_id')
            ->where('field_name', '=', self::RELATION_CREW)
            ->where('is_future', '=', false)
            ->with('person');
    }

    public function originalCasts(): HasMany
    {
        return $this->hasMany(EventPerson::class, 'event_id')
            ->where('field_name', '=', self::RELATION_CASTS)
            ->where('is_original', '=', true)
            ->with('person');
    }

    public function futureCrew(): HasMany
    {
        return $this->hasMany(EventPerson::class, 'event_id')
            ->where('field_name', '=', self::RELATION_CREW)
            ->where('is_future', '=', true)
            ->with('person');
    }

    public function acts(): HasMany
    {
        return $this->hasMany(EventAct::class, 'event_id')
            ->where('is_future', '=', false);
    }

    public function futureActs(): HasMany
    {
        return $this->hasMany(EventAct::class, 'event_id')
            ->where('is_future', '=', true);
    }

    /**
     * Editions of current show
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrenEditions(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->orderBy('created_at', 'asc')
            ->with(self::RELATION_SHOW_TYPES);
    }

    /**
     * Events that include the current event
     *
     */
    public function parentEdition()
    {
        return $this->hasOne(self::class, 'id', 'parent_id')
            ->with('childrenEditions');
        // return Event::where('id', '=', $this->parent_id)->with('childrenEditions');
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(EventSeason::class, 'event_id',);
    }

    public function venue(): BelongsToMany
    {
        return $this->belongsToMany(Venue::class, 'event_venue')
            ->withPivot(
                [
                    'start_year',
                    'start_month',
                    'start_day',
                    'end_year',
                    'end_month',
                    'end_day',
                ]
            );
    }

    public function activeVenues()
    {
        return $this->venue()->whereNotNull('coordinates');
    }

    /**
     * Current event rating
     *
     * @return float|null
     */
    public function getRatingAttribute(): ?float
    {
        $rating = $this->hasMany(EventApplauds::class, 'event_id')->average('rating');
        $mark   = round($rating, 1);

        return $mark > 0 ? $mark : null;
    }

    public function getUserRatingAttribute(): ?float
    {
        if (Auth::guard(BaseAppGuards::USER)->check()) {
            $rating = EventApplauds::where('event_id', '=', $this->id)
                ->where('user_id', '=', Auth::guard(BaseAppGuards::USER)->user()->id)
                ->first()?->rating;

            return $rating == 0 ? null : (float) $rating;
        }

        return null;
    }

    /**
     * @param $file
     *
     * @return string
     */
    public function getPosterAttribute($file)
    {
        if (is_null($file) || self::$withoutUrl === true) {
            return $file;
        }

        return config('app.domain') . '/storage' . $file;
    }

    public function imageFieldName(): string
    {
        return 'poster';
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
        return $query->orderByRaw('poster IS NOT NULL desc, created_at desc');
    }

    /**
     * Order by pivot rating column
     *
     * @param        $query
     * @param string $direction
     */
    public function scopeRatingSorting($query, string $direction)
    {
        $query->leftJoin('event_applauds', 'event_applauds.event_id', '=', 'events.id')
            ->orderByRaw(
                "avg(event_applauds.rating) $direction NULLS LAST"
            )
            ->groupBy('event_applauds.id', 'events.id')
            ->select('events.*');
    }
}
