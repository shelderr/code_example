<?php

namespace App\Traits\Relations;

use App\Models\Links;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Linksable
{
    public function links(): MorphToMany
    {
        return $this->morphToMany(Links::class, 'linkable', 'linkable')
            ->orderBy('created_at', 'asc');
    }
}
