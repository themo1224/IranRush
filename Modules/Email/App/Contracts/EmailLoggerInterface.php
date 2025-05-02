<?php

namespace Modules\Email\App\Contracts;

interface EmailLoggerInterface
{
    public function logSuccess($to, string $subject, string $view, bool $queued = false): void;
    public function logError($to, string $subject, string $error): void;
} 