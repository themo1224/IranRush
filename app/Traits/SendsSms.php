<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Modules\Auth\Services\SmsService;

trait SendsSms
{
    /**
     * Send an SMS using the SmsService.
     *
     * @param string $phoneNumber
     * @param string $message
     * @return bool
     */
    public function sendSms(string $phoneNumber, string $message): bool
    {
        try {
            $smsService = app(SmsService::class);
            return $smsService->sendVerificationCode($phoneNumber, $message);
        } catch (\Exception $e) {
            Log::error('SMS Sending Failed: ' . $e->getMessage());
            return false;
        }
    }
}
