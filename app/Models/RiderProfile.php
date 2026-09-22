<?php

namespace App\Models;

use Database\Factories\RiderProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiderProfile extends Model
{
    /** @use HasFactory<RiderProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'birth_date',
        'phone', 'contact_email', 'self_reported_grade_id', 'national_registry_number', 'status',
        'data_processing_consent_at', 'had_physical_journal', 'activated_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'data_processing_consent_at' => 'datetime',
            'had_physical_journal' => 'boolean',
            'activated_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<CenterRiderMembership, $this> */
    public function centerMemberships(): HasMany
    {
        return $this->hasMany(CenterRiderMembership::class);
    }

    /** @return HasMany<RidingActivity, $this> */
    public function activities(): HasMany
    {
        return $this->hasMany(RidingActivity::class);
    }

    /** @return HasMany<GradeAward, $this> */
    public function gradeAwards(): HasMany
    {
        return $this->hasMany(GradeAward::class);
    }

    /** @return HasMany<GuardianRelationship, $this> */
    public function guardians(): HasMany
    {
        return $this->hasMany(GuardianRelationship::class);
    }
}
