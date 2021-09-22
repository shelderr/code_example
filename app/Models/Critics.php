<?php

namespace App\Models;

use App\Models\Helpers\CriticsInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critics extends Model implements CriticsInterface
{
    use HasFactory;

    protected $fillable = [
        'url',
        'user_id',
        'admin_id',
        'description',
    ];

    protected $casts = [
        'url'      => 'string',
        'user_id'  => 'integer',
        'admin_id' => 'integer',
        'description' => 'string',
    ];

    protected $hidden = ['user_id', 'admin_id', 'pivot'];

    public const ALLOWED_TYPES = [
        self::SHOWS_ENTITY,
        self::EVENTS_ENTITY,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
