<?php

namespace App\Models\Events;

use App\Models\Helpers\Event\AwardsInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Awards extends Model implements AwardsInterface
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'type',
    ];

    protected $casts = [
        'event_id' => 'integer',
        'name'     => 'string',
        'type'     => 'string',
    ];

    protected $hidden = ['event_id', 'pivot', 'type'];

    public function getType(): string
    {
        return $this->type;
    }

    public static function getTypes(): array
    {
        return [self::TYPE_ACTS, self::TYPE_JURY];
    }
}
