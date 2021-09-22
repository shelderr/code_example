<?php

namespace App\Models;

use App\Models\Events\EventApplauds;
use App\Models\Helpers\BaseUsersModelInterface;
use App\Models\Helpers\JWTAuthModel;
use App\Models\Helpers\MultiSizeImageAccessor;
use App\Models\User\Activity;
use App\Services\Base\BaseAppGuards;
use App\Traits\DeletedUserAccessors;
use App\Traits\UsesUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends JWTAuthModel implements BaseUsersModelInterface
{
    use UsesUUID, HasFactory, Notifiable, MultiSizeImageAccessor, SoftDeletes, DeletedUserAccessors;

    public static array $allRelations = [self::PERSONALITY_RELATION];

    public static array $linkStatuses = [self::LINK_STATUS_ACCEPTED, self::LINK_STATUS_PENDING];

    protected $table = 'users';

    public static bool $withoutUrl = false;

    /** @var bool Allows you to see the personal data of the user who applied for deletion
     * @class  DeletedUserAccessors
     */
    public static bool $showRemovedUserFields = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'active',
        'user_name',
        'password',
        'blocked',
        'photo',
        'block_reasons',
        'reject_reasons',
        'remember_token',
        'last_login',
        'last_activity',
        'google_id',
        'facebook_id',
        'news_subscription',
        'system_notifications_subscription',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'uuid',
        'google_id',
        'facebook_id',
        'email_confirmed',
        'block_reasons',
        'is_blocked',
        'is_deleted',
        'deleted_at',
        'google2fa_enabled',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email'                             => 'string',
        'active'                            => 'boolean',
        'user_name'                         => 'string',
        'password'                          => 'string',
        'blocked'                           => 'boolean',
        'photo'                             => 'string',
        'block_reasons'                     => 'string',
        'reject_reasons'                    => 'string',
        'remember_token'                    => 'string',
        'last_login'                        => 'datetime',
        'last_activity'                     => 'datetime',
        'google_id'                         => 'string',
        'facebook_id'                       => 'string',
        'email_verified_at'                 => 'datetime',
        'news_subscription'                 => 'boolean',
        'system_notifications_subscription' => 'boolean',
    ];

    protected $appends = [
        'personality_link_status',
        'MultiSizeImages',
        // 'password_exists'
    ];

    /**
     * Check if user have applauds for current event/show
     *
     * @param int $eventId
     *
     * @return bool
     */
    public function hasApplauds(int $eventId): bool
    {
        return $this->hasMany(EventApplauds::class, 'user_id')
            ->where('event_id', '=', $eventId)
            ->exists();
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.' . $this->id;
    }

    /**
     * User has many activities
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class)->orderBy('created_at', 'desc');
    }

    /**
     * Link to user personality
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function personalityLink(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Persons::class, 'user_person', 'user_id', 'person_id');
    }

    /**
     * Attribute to person verification status
     *
     * @return mixed
     */
    public function getPersonalityLinkStatusAttribute(): mixed
    {
        return $this->personalityLink()->withPivot('status')->first()?->pivot->status;
    }

    public function getPasswordExistsAttribute()
    {
        if (\Auth::guard(BaseAppGuards::USER)->check()) {
            return is_null($this->password) ? false : true;
        }
    }

    /**
     * @param $photo
     *
     * @return string
     */
    public function getPhotoAttribute($photo)
    {
        if (self::$showRemovedUserFields == false) {
            return null;
        }

        if (is_null($photo) || self::$withoutUrl === true) {
            return $photo;
        }

        return config('app.domain') . '/storage' . $photo;
    }

    public function imageFieldName(): string
    {
        return 'photo';
    }
}
