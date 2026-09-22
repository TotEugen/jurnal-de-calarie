<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CenterRiderMembership extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_initial_validation' => 'boolean',
            'validated_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }
}
