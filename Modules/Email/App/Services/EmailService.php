<?php

namespace Modules\Email\App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Modules\Email\App\Mail\BaseMail;

class EmailService
{
    public function send($to, $subject, $view, $data = [], $attachments = []) {
        try {
            Mail::to($to)->send(new BaseMail($subject, $view, $data, $attachments));
            return true;
            
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return false;
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
