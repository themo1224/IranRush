<?php

namespace Modules\Email\App\Exceptions;

use Throwable;

class EmailSendingException extends \RuntimeException
{
    public function __construct(
    
        string $message = "",
        public array $context = [],
        int $code = 0,
        ?Throwable $previous = null
    ){
        parent::__construct($message, $code, $previous);

    }
    
}