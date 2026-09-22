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
        'national_registry_number', 'status', 'activated_at',
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date', 'activated_at' => 'datetime'];
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
}
