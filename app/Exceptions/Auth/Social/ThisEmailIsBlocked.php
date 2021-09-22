<?php

namespace App\Exceptions\Auth\Social;

use App\Exceptions\ErrorMessages;
use Illuminate\Http\Response;

class ThisEmailIsBlocked extends \Exception
{
    protected $code = Response::HTTP_NOT_ACCEPTABLE;

    protected $message = ErrorMessages::EMAIL_IS_BLOCKED;
}
