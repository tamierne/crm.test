<?php

namespace App\Exceptions;

use Exception;

class StatusNotFoundException extends Exception
{
    protected $message = 'Status not found';
}
