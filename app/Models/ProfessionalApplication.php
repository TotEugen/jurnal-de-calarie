<?php

namespace App\Models;

use App\Enums\CenterApplicationStatus;
use Database\Factories\ProfessionalApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProfessionalApplication extends Model
{
    /** @use HasFactory<ProfessionalApplicationFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => CenterApplicationStatus::class,
            'qualification_obtained_at' => 'date',
            'requests_evaluator_authorization' => 'boolean',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(ProfessionalProfile::class, 'professional_profile_id');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function statusHistory(): MorphMany
    {
        return $this->morphMany(ApplicationStatusHistory::class, 'application')->latest();
    }
}
