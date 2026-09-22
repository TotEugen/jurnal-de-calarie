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
        'phone', 'self_reported_grade_id', 'national_registry_number', 'status',
        'data_processing_consent_at', 'activated_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'data_processing_consent_at' => 'datetime',
            'activated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function centerMemberships(): HasMany
    {
        return $this->hasMany(CenterRiderMembership::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(RidingActivity::class);
    }

    public function gradeAwards(): HasMany
    {
        return $this->hasMany(GradeAward::class);
    }
}
