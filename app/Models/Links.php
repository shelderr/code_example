<?php

namespace App\Models;

use App\Models\Helpers\LinksInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Links extends Model implements LinksInterface
{
    use HasFactory;

    protected $fillable = [
        'url',
        'user_id',
        'admin_id',
        'description',
    ];

    protected $casts = [
        'url'         => 'string',
        'user_id'     => 'integer',
        'admin_id'    => 'integer',
        'description' => 'string',
    ];

    protected $hidden = ['user_id', 'admin_id', 'pivot'];

    public const ALLOWED_TYPES = [
        self::PERSONS_ENTITY,
        self::COLLECTIVES_ENTITY,
        self::EVENTS_ENTITY,
        self::SHOWS_ENTITY,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
