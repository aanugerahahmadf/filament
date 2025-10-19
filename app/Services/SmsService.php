<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send OTP via SMS
     *
     * @param User $user
     * @param string $otp
     * @return bool
     */
    public function sendOtp(User $user, string $otp): bool
    {
        if (empty($user->phone_number)) {
            return false;
        }

        try {
            // In a real implementation, you would integrate with an SMS gateway like Twilio, Nexmo, etc.
            // For now, we'll log the OTP for demonstration purposes
            Log::info("SMS OTP sent to {$user->phone_number}: {$otp}");

            // Example implementation with a real SMS service:
            /*
            $client = new \Twilio\Rest\Client(config('services.twilio.sid'), config('services.twilio.token'));
            $client->messages->create(
                $user->phone_number,
                [
                    'from' => config('services.twilio.from'),
                    'body' => "Your OTP code is: {$otp}. This code will expire in 5 minutes."
                ]
            );
            */

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SMS OTP: ' . $e->getMessage());
            return false;
        }
    }
}
