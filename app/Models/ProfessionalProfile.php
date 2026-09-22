<?php

namespace App\Models;

use Database\Factories\ProfessionalProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfessionalProfile extends Model
{
    /** @use HasFactory<ProfessionalProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'license_number', 'federation_status', 'is_evaluator',
        'authorized_at', 'evaluator_authorized_at', 'qualification_grade',
        'qualification_identifier', 'passport_number', 'qualification_obtained_at',
        'issuing_authority',
    ];

    protected function casts(): array
    {
        return [
            'is_evaluator' => 'boolean',
            'authorized_at' => 'datetime',
            'evaluator_authorized_at' => 'datetime',
            'qualification_obtained_at' => 'date',
        ];
    }

    /** @return BelongsTo<User, $this> */
    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<CenterProfessionalAffiliation, $this> */
    /** @return HasMany<CenterProfessionalAffiliation, $this> */
    public function affiliations(): HasMany
    {
        return $this->hasMany(CenterProfessionalAffiliation::class);
    }

    /** @return HasMany<RidingActivity, $this> */
    /** @return HasMany<RidingActivity, $this> */
    public function activities(): HasMany
    {
        return $this->hasMany(RidingActivity::class);
    }

    /** @return HasMany<ProfessionalApplication, $this> */
    /** @return HasMany<ProfessionalApplication, $this> */
    public function applications(): HasMany
    {
        return $this->hasMany(ProfessionalApplication::class);
    }
}
