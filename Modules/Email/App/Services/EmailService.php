<?php

namespace Modules\Email\App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Modules\Email\App\Exceptions\EmailSendingException;
use Modules\Email\App\Mail\BaseMail;

class EmailService
{
    public function send($to, $subject, $view="", $data = [], $attachments = [])
    {
        try {
            if (!view()->exists($view)) {
                throw new \Exception("View '{$view}' does not exist");
            }
            Mail::to($to)->send(new BaseMail($subject, $view, $data, $attachments));
            return true;
        } catch (\Exception $e) {
            throw new EmailSendingException(
                message: "Failed to send email: " . $e->getMessage(),
                context: [
                    'recipient' => $to,
                    'subject' => $subject,
                    'view' => $view,
                    'error' => $e->getMessage()
                ],
                previous: $e
            );
        }
    }

    public function queue($to, $subject, $view, $data = [], $attachments = [])
    {
        try {
            Mail::to($to)->queue(new BaseMail($subject, $view, $data, $attachments));
            return true;
        } catch (\Exception $e) {
            Log::error('Email queuing failed: ' . $e->getMessage());
            return false;
        }
    }
}
