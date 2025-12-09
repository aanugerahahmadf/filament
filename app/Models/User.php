<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\OtpVerificationMail;
use App\Services\SmsService;
use App\Services\WhatsAppService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'google2fa_secret',
        'google2fa_enabled',
        'otp_code',
        'otp_expires_at',
        'email_verified_at',
        'place_of_birth',
        'city',
        'date_of_birth',
        'phone_number',
        'phone_verified',
        'phone_verification_method',
        'phone_verified_at',
        'avatar',
        'locale',
        'theme_color',
        'avatar_url',
        'custom_fields',
        'has_strong_password',
        'status',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
        'otp_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'google2fa_enabled' => 'boolean',
            'otp_expires_at' => 'datetime',
            'date_of_birth' => 'date',
            'custom_fields' => 'array',
            'has_strong_password' => 'boolean',
            'phone_verified' => 'boolean',
            'phone_verified_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the user's avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        // First check if there's an external avatar URL
        if (!empty($this->attributes['avatar_url'])) {
            return $this->attributes['avatar_url'];
        }

        // Then check for local avatar
        if (!empty($this->attributes['avatar'])) {
            return asset('storage/' . $this->attributes['avatar']);
        }

        // Return a default avatar or use initials as fallback
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0D8ABC&color=fff';
    }

    /**
     * Set the user's avatar URL
     */
    public function setAvatarUrlAttribute(?string $value): void
    {
        $this->attributes['avatar_url'] = $value;
    }

    // Add accessor methods for custom fields (for non-registration fields)
    public function getAddressAttribute(): ?string
    {
        return $this->custom_fields['address'] ?? null;
    }

    // Add mutator methods for custom fields (for non-registration fields)
    public function setAddressAttribute(?string $value): void
    {
        $customFields = $this->custom_fields ?? [];
        $customFields['address'] = $value;
        $this->attributes['custom_fields'] = json_encode($customFields);
    }

    /**
     * Get the user's notifications
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user's unread notifications
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * Get messages sent by the user
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'from_user_id');
    }

    /**
     * Get messages received by the user
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }

    /**
     * Get all messages (sent and received) for the user
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'from_user_id')
            ->orWhere('to_user_id', $this->id);
    }

    /**
     * Get unread messages for the user
     */
    public function unreadMessages()
    {
        return $this->receivedMessages()->whereNull('read_at');
    }

    /**
     * Get the user's preferred verification method
     *
     * @return string
     */
    public function getPreferredVerificationMethod(): string
    {
        // If phone number exists and phone is verified, return the verification method used
        if (!empty($this->phone_number) && $this->phone_verified && $this->phone_verification_method) {
            return $this->phone_verification_method;
        }

        // If phone number exists but not verified, prefer SMS
        if (!empty($this->phone_number)) {
            return 'sms';
        }

        // Default to email
        return 'email';
    }

    /**
     * Generate and send OTP via email using custom template
     */
    public function sendOtp()
    {
        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Set expiration time (5 minutes from now)
        $expiresAt = now()->addMinutes(5);

        // Save OTP to user
        $this->update([
            'otp_code' => bcrypt($otp),
            'otp_expires_at' => $expiresAt,
        ]);

        // Send OTP via email using custom template
        try {
            Mail::to($this->email)->send(new OtpVerificationMail($otp, $this->name));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Send OTP via SMS
     */
    public function sendSmsOtp(): bool
    {
        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Set expiration time (5 minutes from now)
        $expiresAt = now()->addMinutes(5);

        // Save OTP to user
        $this->update([
            'otp_code' => bcrypt($otp),
            'otp_expires_at' => $expiresAt,
            'phone_verification_method' => 'sms',
        ]);

        // Send OTP via SMS
        $smsService = app(SmsService::class);
        return $smsService->sendOtp($this, $otp);
    }

    /**
     * Send OTP via WhatsApp
     */
    public function sendWhatsAppOtp(): bool
    {
        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Set expiration time (5 minutes from now)
        $expiresAt = now()->addMinutes(5);

        // Save OTP to user
        $this->update([
            'otp_code' => bcrypt($otp),
            'otp_expires_at' => $expiresAt,
            'phone_verification_method' => 'whatsapp',
        ]);

        // Send OTP via WhatsApp
        $whatsAppService = app(WhatsAppService::class);
        return $whatsAppService->sendOtp($this, $otp);
    }

    /**
     * Mark phone as verified
     */
    public function markPhoneAsVerified(): bool
    {
        return $this->forceFill([
            'phone_verified' => true,
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Check if a password is strong according to our requirements
     *
     * @param string $password
     * @return bool
     */
    public function isPasswordStrong(string $password): bool
    {
        return strlen($password) >= 8 &&
               preg_match('/[A-Z]/', $password) &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[0-9]/', $password) &&
               preg_match('/[@$!%*?&]/', $password);
    }
}
