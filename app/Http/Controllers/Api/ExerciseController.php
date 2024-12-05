<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\ExerciseCategory;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        $exercises = Exercise::with('category')
            ->when($request->category_id, function($query, $category_id) {
                return $query->where('category_id', $category_id);
            })
            ->when($request->difficulty_level, function($query, $difficulty_level) {
                return $query->where('difficulty_level', $difficulty_level);
            })
            ->get();

        return response()->json($exercises);
    }

    public function categories()
    {
        $categories = ExerciseCategory::withCount('exercises')->get();
        return response()->json($categories);
    }

    public function show(Exercise $exercise)
    {
        return response()->json($exercise->load('category'));
    }
}