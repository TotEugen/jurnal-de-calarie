<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionalRiderAccess extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'granted_at' => 'datetime',
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }
}
