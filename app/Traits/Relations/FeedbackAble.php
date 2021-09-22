<?php

namespace App\Traits\Relations;

use App\Models\Feedback;

trait FeedbackAble
{
    public function feedback()
    {
        return $this->morphToMany(Feedback::class, 'feedbackables');
    }
}
