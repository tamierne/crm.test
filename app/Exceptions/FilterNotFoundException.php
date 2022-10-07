<?php

namespace App\Exceptions;

use Exception;

class FilterNotFoundException extends Exception
{
    protected $message = 'Filter not found';
}
