<?php

namespace App\Models;

use App\Models\Events\Event;
use App\Models\Helpers\CategoryInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model implements CategoryInterface
{
    use HasFactory;

    protected $hidden = ['parent_id', 'created_at', 'updated_at', 'type', 'pivot'];

    public const ALL_CATEGORIES = [
        self::PRODUCTION_TYPE,
        self::SHOW_AUDIENCE_TYPE,
        self::SHOW_TYPE,
        self::EVENT_TYPE,
        self::COLLECTIVE_CATEGORY,
        self::VENUE_CATEGORY,
    ];

    public function childrens()
    {
        return $this->hasMany(self::class, 'parent_id')->with('childrens');
    }

    public function events()
    {
        return $this->morphedByMany(Event::class, 'categoriesable');
    }
}
