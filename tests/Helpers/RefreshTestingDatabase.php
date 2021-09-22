<?php declare(strict_types=1);

namespace Tests\Helpers;

/**
 * Trait RefreshTestingDatabase
 * @package Tests\Helpers
 */
trait RefreshTestingDatabase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed --env=testing');
    }

    public function tearDown():
    void
    {
        $this->artisan('migrate:reset --env=testing');
        parent::tearDown();
    }
}
