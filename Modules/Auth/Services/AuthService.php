<?php

namespace Modules\Auth\Services;


use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Modules\Auth\App\Models\OtpCode;
use Modules\Auth\App\Models\User;

class AuthService
{
    public function generateOtp(string $phoneNumber): string
    {
        $otp = random_int(100000, 999999);

        Redis::set("otp:$phoneNumber", $otp, 'EX', 360);

        return $otp;
    }

    public function verifyOtp(string $phoneNumber, string $otp): bool
    {
        $storedOtp = Redis::get("otp:$phoneNumber");

        //check if OTP exists and matches
        if ($storedOtp && $storedOtp === $otp) {
            Redis::del("otp:$phoneNumber");
            return true;
        }

        return false;
    }

    public function registerUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
        ]);
    }

    public function findOrCreateUser(string $phoneNumber): User
    {
        return User::firstOrCreate(
            ['phone_number' => $phoneNumber],
            ['name' => 'New User'] // Default name for new users
        );
    }
}
