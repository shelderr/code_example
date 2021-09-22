<?php

namespace App\Exceptions\Auth\Social;

use App\Exceptions\ErrorMessages;
use Illuminate\Http\Response;

class UsernameNotFoundException extends \Exception
{
    protected $code = Response::HTTP_FORBIDDEN;

    protected $message = ErrorMessages::USERNAME_NOT_EXIST;
}
