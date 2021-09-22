<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventApplauds extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'rating',
    ];

    protected $casts = [
        'event_id' => 'integer',
        'user_id'  => 'integer',
        'rating'   => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
