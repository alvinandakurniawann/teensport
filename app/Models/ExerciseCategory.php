<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExerciseCategory extends Model
{
    use HasFactory;

    protected $table = 'exercise_categories';
    
    protected $fillable = [
        'name',
        'description'
    ];

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class, 'category_id');
    }
}