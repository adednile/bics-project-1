<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chama extends Model
{
    protected $fillable = [
        'name',
        'location',
        'description',
        'currency',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
