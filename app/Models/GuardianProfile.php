<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardianProfile extends Model
{
    protected $fillable = ['user_id', 'first_name', 'last_name', 'birth_date', 'phone'];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
