<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeAward extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['awarded_at' => 'datetime'];
    }

    /** @return BelongsTo<Grade, $this> */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }
}
