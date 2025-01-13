<?php

namespace Modules\Auth\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\SendsSms;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Auth\App\Http\Requests\RegisterRequest;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Modules\Auth\App\Http\Requests\VerifyOtpRequest;

class RegisterController extends Controller
{
    use SendsSms;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a user by sending an OTP.
     */

    public function register(RegisterRequest $request)
    {
        $otp = $this->authService->generateOtp($request->phone_number);

        if (!$this->sendSms($request->phone_number, "Your OTP is : $otp")) {
            return response()->json(['message' => 'Failed to send OTP.'], 500);
        }

        return response()->json(['message' => 'OTP sent successfully.']);
    }

    /**
     * Verify the OTP and register the user.
     */

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        // Verify OTP
        if (!$this->authService->verifyOtp($request->phone_number, $request->otp)) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 422);
        }

        // Register User
        $user = $this->authService->registerUser($request->all());

        //Generates token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'token' => $token,
            'user' => $user,
        ]);
    }
}
