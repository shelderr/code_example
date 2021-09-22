<?php

namespace App\Exceptions\Events;

use App\Exceptions\ErrorMessages;
use Exception;
use Illuminate\Http\Response;

class AlreadyApplauded extends Exception
{
    protected $code = Response::HTTP_FORBIDDEN;

    protected $message = ErrorMessages::ALREADY_APPLAUDED;
}
