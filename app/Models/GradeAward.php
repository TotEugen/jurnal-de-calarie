<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeAward extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['awarded_at' => 'datetime'];
    }
}
