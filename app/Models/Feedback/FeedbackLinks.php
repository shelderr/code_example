<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackLinks extends Model
{
    use HasFactory;

    protected $fillable = [
        'feedback_id',
        'link'
    ];
}
