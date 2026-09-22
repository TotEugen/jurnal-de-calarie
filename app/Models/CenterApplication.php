<?php

namespace App\Models;

use App\Enums\CenterApplicationStatus;
use Database\Factories\CenterApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CenterApplication extends Model
{
    /** @use HasFactory<CenterApplicationFactory> */
    use HasFactory;

    protected $fillable = [
        'equestrian_center_id', 'submitted_by', 'reviewed_by', 'status',
        'revision', 'applicant_notes', 'review_notes', 'submitted_at', 'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => CenterApplicationStatus::class,
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(EquestrianCenter::class, 'equestrian_center_id');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function statusHistory(): MorphMany
    {
        return $this->morphMany(ApplicationStatusHistory::class, 'application')->latest();
    }
}
