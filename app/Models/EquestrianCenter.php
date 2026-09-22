<?php

namespace App\Models;

use App\Enums\AffiliationStatus;
use Database\Factories\EquestrianCenterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquestrianCenter extends Model
{
    /** @use HasFactory<EquestrianCenterFactory> */
    use HasFactory;

    protected $fillable = [
        'legal_name', 'slug', 'fiscal_code', 'registration_number', 'email',
        'phone', 'website', 'county', 'locality', 'address', 'affiliation_status',
        'affiliated_at', 'affiliation_expires_at', 'is_public', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'affiliation_status' => AffiliationStatus::class,
            'affiliated_at' => 'datetime',
            'affiliation_expires_at' => 'datetime',
            'is_public' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /** @return HasMany<CenterApplication, $this> */
    /** @return HasMany<CenterApplication, $this> */
    public function applications(): HasMany
    {
        return $this->hasMany(CenterApplication::class);
    }

    /** @return HasMany<CenterMembership, $this> */
    /** @return HasMany<CenterMembership, $this> */
    public function memberships(): HasMany
    {
        return $this->hasMany(CenterMembership::class);
    }

    /** @return HasMany<CenterProfessionalAffiliation, $this> */
    /** @return HasMany<CenterProfessionalAffiliation, $this> */
    public function professionalAffiliations(): HasMany
    {
        return $this->hasMany(CenterProfessionalAffiliation::class);
    }

    /** @return HasMany<CenterRiderMembership, $this> */
    /** @return HasMany<CenterRiderMembership, $this> */
    public function riderMemberships(): HasMany
    {
        return $this->hasMany(CenterRiderMembership::class);
    }
}
