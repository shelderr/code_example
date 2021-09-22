<?php

namespace App\Traits;

use App\Enums\BaseAppEnum;
use App\Scopes\IsBlockedScope;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\stringStartsWith;

trait IsBlockedScopeTrait
{
    protected static function booted()
    {
        static::addGlobalScope(new IsBlockedScope());
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function blockSwitch(): mixed
    {
        DB::transaction(
            function () {
                $this->is_blocked = $this->is_blocked == false ? true : false;
                $this->save();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );

        return $this;
    }
}
