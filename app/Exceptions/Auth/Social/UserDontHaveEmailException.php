<?php

namespace App\Exceptions\Auth\Social;

use App\Exceptions\ErrorMessages;
use Illuminate\Http\Response;

class UserDontHaveEmailException extends \Exception
{
    protected $code = Response::HTTP_NOT_ACCEPTABLE;

    protected $message = ErrorMessages::USER_HAS_NO_EMAIL;
}
