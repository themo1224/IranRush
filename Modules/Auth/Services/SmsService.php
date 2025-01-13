<?php

namespace Modules\Auth\Services;

use Illuminate\Support\Facades\Log;
use Ipe\Sdk\Facades\SmsIr;

class SmsService
{
    /**
     * Send a verification code via SMS.
     */
    public function sendVerificationCode(string $phoneNumber, string $message): bool
    {
        try {
            // SMS.ir example (replace with your provider details)
            $templateId = 100000; // شناسه الگو
            $parameters = [['name' => 'Code', 'value' => $message]];
            $response = SmsIr::verifySend($phoneNumber, $templateId, $parameters);
            return true;
        } catch (\Exception $e) {
            Log::error('SMS Sending Error: ' . $e->getMessage());
            return false;
        }
    }
}
