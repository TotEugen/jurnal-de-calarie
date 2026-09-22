<?php

namespace App\Models;

use App\Enums\CenterMembershipRole;
use Database\Factories\CenterMembershipFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CenterMembership extends Model
{
    /** @use HasFactory<CenterMembershipFactory> */
    use HasFactory;

    protected $fillable = [
        'equestrian_center_id', 'user_id', 'role', 'status', 'validated_by', 'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'role' => CenterMembershipRole::class,
            'validated_at' => 'datetime',
        ];
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(EquestrianCenter::class, 'equestrian_center_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
