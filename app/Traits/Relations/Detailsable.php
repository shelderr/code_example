<?php

namespace App\Traits\Relations;

use App\Models\Details;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Detailsable
{
    public function details(): MorphToMany
    {
        return $this->morphToMany(Details::class, 'detailsable', 'detailsable')
            ->orderBy('created_at', 'asc');
    }
}
