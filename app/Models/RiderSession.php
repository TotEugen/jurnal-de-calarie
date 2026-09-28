<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiderSession extends Model
{
    protected $fillable = [
        'rider_profile_id', 'equestrian_center_id', 'session_number', 'session_date',
        'session_time', 'center_name', 'duration_minutes', 'activity_types',
        'other_activity', 'horse_name', 'learned_today', 'key_takeaway',
        'next_experience', 'instructor_name', 'rating', 'status', 'submitted_at',
        'monitor_confirmed_by', 'monitor_confirmed_at', 'center_confirmed_by', 'center_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'activity_types' => 'array',
            'submitted_at' => 'datetime',
            'monitor_confirmed_at' => 'datetime',
            'center_confirmed_at' => 'datetime',
        ];
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
}
