<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPersonRole extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'event_person_role';

    protected $hidden = ['event_person_id', 'updated_at'];

    protected $fillable = [
        'name',
        'event_person_id',
    ];

    protected $casts = [
        'name'            => 'string',
        'event_person_id' => 'string',
    ];
}
