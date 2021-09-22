<?php

namespace App\Models\Collectives;

use App\Models\Persons;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectivePerson extends Model
{
    use HasFactory;

    protected $table = 'collective_person';

    protected $fillable = [
        'collective_id',
        'person_id',
        'years',
        'role',
    ];

    protected $casts = [
        'collective_id' => 'integer',
        'person_id'     => 'integer',
        'years'         => 'array',
        'role'          => 'string',
    ];

    protected $hidden = ['collective_id', 'person_id'];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Persons::class, 'person_id');
    }
}
