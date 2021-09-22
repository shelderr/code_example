<?php

namespace App\Services\Media;

use App\Repositories\MediaRepository;

class MediaService extends MediaRepository
{

    public function index(string $type): mixed
    {
        return parent::index($type);
    }
}
