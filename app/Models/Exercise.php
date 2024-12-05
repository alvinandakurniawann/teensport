<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'difficulty_level',
        'instructions',
        'image_url'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExerciseCategory::class);
    }

    public function workouts(): BelongsToMany
    {
        return $this->belongsToMany(Workout::class, 'workout_exercises')
            ->withPivot('sets', 'reps', 'duration')
            ->withTimestamps();
    }
}