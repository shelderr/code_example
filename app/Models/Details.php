<?php

namespace App\Models;

use App\Models\Helpers\DetailsInterface;
use App\Models\Helpers\MultiSizeImageAccessor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details extends Model implements DetailsInterface
{
    use HasFactory, MultiSizeImageAccessor;

    protected $fillable = [
        'image',
        'image_author',
        'image_source',
        'image_permission',
        'description',
    ];

    protected $casts = [
        'image'            => 'string',
        'description'      => 'string',
        'image_author'     => 'string',
        'image_source'     => 'string',
        'image_permission' => 'string',
    ];

    protected $appends = ['MultiSizeImages'];

    protected $hidden = ['pivot'];

    public static bool $withoutUrl = false;

    public const ALLOWED_TYPES = [self::PERSONS_ENTITY, self::COLLECTIVES_ENTITY, self::EVENTS_ENTITY, self::SHOWS_ENTITY];

    public const IMAGE_PERMISSIONS_ENUMS = [self::IMAGE_PERMISSION_OWNED, self::IMAGE_PERMISSION_SOURCED];

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
}
