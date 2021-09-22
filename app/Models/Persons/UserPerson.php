<?php

namespace App\Models\Persons;

use App\Models\Persons;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPerson extends Model
{
    use HasFactory;

    protected $table = 'user_person';

    protected $hidden = ['user_id', 'person_id'];

    protected $with = ['user', 'person'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function person()
    {
        return $this->belongsTo(Persons::class, 'person_id');
    }
}
