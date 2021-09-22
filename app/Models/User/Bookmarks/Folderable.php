<?php

namespace App\Models\User\Bookmarks;

use App\Models\Events\Event;
use App\Models\Persons;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folderable extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'folderable';

    protected $fillable = [
        'bookmark_folder_id',
        'folderable_id',
        'folderable_type',
    ];
}
