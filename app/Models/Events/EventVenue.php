<?php

namespace App\Models\Events;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventVenue extends Pivot
{
    use HasFactory;

    protected $table = 'event_venue';

    protected $fillable = [
        'event_id',
        'venue_id',
        'start_year',
        'start_month',
        'start_day',
        'end_year',
        'end_month',
        'end_day',
    ];

    protected $casts = [
        'event_id'    => 'integer',
        'persons_id'  => 'integer',
        'start_year'  => 'integer',
        'start_month' => 'integer',
        'start_day'   => 'integer',
        'end_year'    => 'integer',
        'end_month'   => 'integer',
        'end_day'     => 'integer',
    ];

    protected $hidden = ['event_id', 'persons_id', 'venue_id'];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'id');
    }
}
