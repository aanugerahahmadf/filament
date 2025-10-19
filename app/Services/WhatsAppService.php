<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send OTP via WhatsApp
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
            // Format phone number (remove any non-digit characters and ensure it starts with country code)
            $phoneNumber = $this->formatPhoneNumber($user->phone_number);

            // For demonstration purposes, we'll log the OTP
            Log::info("WhatsApp OTP sent to {$phoneNumber}: {$otp}");

            // In a real implementation, you would integrate with a WhatsApp Business API
            // Here's an example of how you might implement it with a service like Twilio or similar:

            /*
            // Example implementation with Twilio WhatsApp API
            $response = Http::withBasicAuth(
                config('services.twilio.sid'),
                config('services.twilio.auth_token')
            )->post('https://api.twilio.com/2010-04-01/Accounts/' . config('services.twilio.sid') . '/Messages.json', [
                'From' => 'whatsapp:' . config('services.twilio.whatsapp_from'),
                'To' => 'whatsapp:' . $phoneNumber,
                'Body' => "Your OTP code is: {$otp}. This code will expire in 5 minutes."
            ]);

            if ($response->successful()) {
                return true;
            } else {
                Log::error('Failed to send WhatsApp OTP via Twilio: ' . $response->body());
                return false;
            }
            */

            // Example implementation with 360Dialog WhatsApp API
            /*
            $response = Http::withToken(config('services.whatsapp_360dialog.api_key'))
                ->post(config('services.whatsapp_360dialog.endpoint') . '/messages', [
                    'to' => $phoneNumber,
                    'type' => 'text',
                    'text' => [
                        'body' => "Your OTP code is: {$otp}. This code will expire in 5 minutes."
                    ]
                ]);

            if ($response->successful()) {
                return true;
            } else {
                Log::error('Failed to send WhatsApp OTP via 360Dialog: ' . $response->body());
                return false;
            }
            */

            // For now, we'll return true to simulate successful sending
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp OTP: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format phone number to ensure it starts with country code
     *
     * @param string $phoneNumber
     * @return string
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-digit characters
        $phoneNumber = preg_replace('/[^\d]/', '', $phoneNumber);

        // If the number starts with 0, replace it with country code (assuming +62 for Indonesia)
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        }

        // If the number doesn't start with +, add it
        if (substr($phoneNumber, 0, 1) !== '+') {
            $phoneNumber = '+' . $phoneNumber;
        }

        return $phoneNumber;
    }
}
