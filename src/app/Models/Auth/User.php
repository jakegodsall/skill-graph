<?php

namespace App\Models\Auth;

use App\Models\Auth\SocialAccount;
use App\Notifications\EmailChangeSuccessNotification;
use App\Notifications\NotifyOriginalEmailAboutChangeNotification;
use App\Notifications\VerifyNewEmailNotification;
use App\Utils\StringUtils;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }

    // Relationships

    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    // Attributes

    public function roleString(): Attribute
    {
        return Attribute::make(
            get: function() {
                return $this->roles()
                    ->pluck('name')
                    ->map(fn ($role) => StringUtils::titleCase($role))
                    ->implode(', ') ?? '';
            }
        );
    }

    // Custom methods

    /**
     * Set random recovery codes for 2FA.
     */
    public function setRecoveryCodes()
    {
        $this->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(
                collect(range(1, 8))->map(fn () => Str::random(10))->all()
            )),
        ])->save();
    }

    // Notifications

    /**
     * Sends an email to the original email address to notify the user that email change attempt made.
     * Runs on a queue.
     */
    public function sendEmailChangeNotificationToOriginalEmail(string $newEmail): void
    {
        $this->notify(new NotifyOriginalEmailAboutChangeNotification($newEmail));
    }

    /**
     * Sends an email to the new email address with a button to verify the email change.
     * Runs on a queue.
     */
    public function sendEmailVerificationNotificationToNewEmail(string $newEmail): void
    {
        $this->notify(new VerifyNewEmailNotification($newEmail));
    }

    /**
     * Snds an email to the original email address to confirm that the registered address has been updated.
     * Runs on a queue.
     */
    public function sendEmailChangeSuccessNotification(string $newEmail): void
    {
        $this->notify(new EmailChangeSuccessNotification($newEmail));
    }
}
