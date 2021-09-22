<?php

namespace App\Models;

use App\Models\Helpers\Country\CountryInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model implements CountryInterface
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = ['pivot'];
}
