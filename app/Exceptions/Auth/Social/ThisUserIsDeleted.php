<?php

namespace App\Exceptions\Auth\Social;

use App\Exceptions\ErrorMessages;
use Illuminate\Http\Response;

class ThisUserIsDeleted extends \Exception
{
    protected $code = Response::HTTP_NOT_FOUND;

    protected $message = ErrorMessages::USER_NOT_EXIST;
}
