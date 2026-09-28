<?php

namespace App\Models;

use App\Notifications\EmailVerificationCodeNotification;
use Database\Factories\UserFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string|null $email_verification_code_hash
 * @property Carbon|null $email_verification_code_expires_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'email_verification_code_hash', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmailContract
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, MustVerifyEmail, Notifiable, TwoFactorAuthenticatable;

    public function sendEmailVerificationNotification(): void
    {
        $code = (string) random_int(100000, 999999);

        $this->forceFill([
            'email_verification_code_hash' => hash('sha256', $code),
            'email_verification_code_expires_at' => now()->addMinutes(15),
        ])->saveQuietly();

        $this->notify(new EmailVerificationCodeNotification($code));
    }

    /** @return BelongsToMany<Role, $this> */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /** @return HasMany<CenterMembership, $this> */
    public function centerMemberships(): HasMany
    {
        return $this->hasMany(CenterMembership::class);
    }

    /** @return HasMany<CenterApplication, $this> */
    public function centerApplications(): HasMany
    {
        return $this->hasMany(CenterApplication::class, 'submitted_by');
    }

    /** @return HasOne<RiderProfile, $this> */
    public function riderProfile(): HasOne
    {
        return $this->hasOne(RiderProfile::class);
    }

    /** @return HasOne<ProfessionalProfile, $this> */
    public function professionalProfile(): HasOne
    {
        return $this->hasOne(ProfessionalProfile::class);
    }

    /** @return HasMany<GuardianRelationship, $this> */
    public function guardianRelationships(): HasMany
    {
        return $this->hasMany(GuardianRelationship::class, 'guardian_user_id');
    }

    /** @return HasOne<GuardianProfile, $this> */
    public function guardianProfile(): HasOne
    {
        return $this->hasOne(GuardianProfile::class);
    }

    public function hasRole(string $code): bool
    {
        return $this->roles()->where('code', $code)->exists();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verification_code_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
}
