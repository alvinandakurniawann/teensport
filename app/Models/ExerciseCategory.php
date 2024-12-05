<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExerciseCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon'
    ];

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class, 'category_id');
    }
}