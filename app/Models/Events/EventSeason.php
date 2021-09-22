<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSeason extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'image',
        'image_author',
        'image_source',
        'image_permission',
        'description',
        'years',
    ];

    protected $casts = [
        'event_id'         => 'integer',
        'image'            => 'string',
        'image_author'     => 'string',
        'image_source'     => 'string',
        'image_permission' => 'string',
        'description'      => 'string',
        'years'            => 'array',
    ];

    public static bool $withoutUrl = false;

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
}
