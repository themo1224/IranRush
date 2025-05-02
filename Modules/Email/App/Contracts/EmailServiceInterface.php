<?php

namespace Modules\Email\App\Contracts;

interface EmailServiceInterface
{
    /**
     * Send an email immediately
     *
     * @param string|array $to
     * @param string $subject
     * @param string $view
     * @param array $data
     * @param array $attachments
     * @return bool
     */
    public function send($to, string $subject, string $view, array $data = [], array $attachments = []): bool;

    /**
     * Queue an email for sending
     *
     * @param string|array $to
     * @param string $subject
     * @param string $view
     * @param array $data
     * @param array $attachments
     * @return bool
     */
    public function queue($to, string $subject, string $view, array $data = [], array $attachments = []): bool;

    /**
     * Get the mailer instance
     *
     * @return \Illuminate\Mail\Mailer
     */
    public function mailer();
} 