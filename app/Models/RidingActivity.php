<?php

namespace App\Models;

use Database\Factories\RidingActivityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RidingActivity extends Model
{
    /** @use HasFactory<RidingActivityFactory> */
    use HasFactory;

    protected $fillable = [
        'rider_profile_id', 'equestrian_center_id', 'professional_profile_id',
        'performed_at', 'duration_minutes', 'activity_type', 'competencies', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return ['performed_at' => 'datetime', 'competencies' => 'array'];
    }

    /** @return BelongsTo<RiderProfile, $this> */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(RiderProfile::class, 'rider_profile_id');
    }

    /** @return BelongsTo<EquestrianCenter, $this> */
    public function center(): BelongsTo
    {
        return $this->belongsTo(EquestrianCenter::class, 'equestrian_center_id');
    }

    /** @return BelongsTo<ProfessionalProfile, $this> */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(ProfessionalProfile::class, 'professional_profile_id');
    }
}
