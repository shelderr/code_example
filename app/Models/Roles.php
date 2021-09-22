<?php

namespace App\Models;

use App\Models\Helpers\RolesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model implements RolesInterface
{
    use HasFactory;

  /*  public static array $types = [
        self::TYPE_COLLECTIVE,
        self::TYPE_EVENT,
        self::TYPE_PERSONALITY,
        self::TYPE_SHOW,
        self::TYPE_VENUE,
    ];*/

    protected $fillable = [
        'name',
        'parent_id'
    ];

    protected $hidden = ['created_at', 'updated_at', 'pivot'];


    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function childrensOf(int $id)
    {
        return $this->newQuery()->where('parent_id', '=', $id);
    }
}
