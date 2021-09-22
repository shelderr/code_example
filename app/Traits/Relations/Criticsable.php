<?php

namespace App\Traits\Relations;

use App\Models\Critics;
use App\Models\Links;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Criticsable
{
    public function critics(): MorphToMany
    {
        return $this->morphToMany(Critics::class, 'criticsable', 'criticsable')
            ->withTimestamps()
            ->orderBy('created_at', 'asc');
    }
}
