<?php

namespace Mdojr\Scraper\Exception;

use Exception;

class WorldNotLoadedException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}