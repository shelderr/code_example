<?php

declare( strict_types=1 );

namespace App\Jobs;

/**
 * Interface QueuesNames
 *
 * @package App\Jobs
 */
interface QueuesNames
{
    public const DEFAULT = 'default';
    public const JOBS = 'jobs'; // Default laravel queues

    public const QUEUE = [
        self::DEFAULT,
        self::JOBS
    ];
}
