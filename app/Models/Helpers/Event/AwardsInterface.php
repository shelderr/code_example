<?php

namespace App\Models\Helpers\Event;

interface AwardsInterface
{
    public function getType(): string;

    public static function getTypes(): array;

    public const TYPE_ACTS = 'acts';
    public const TYPE_JURY = 'jury';
}
