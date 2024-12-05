<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workout;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function index(Request $request)
    {
        $workouts = $request->user()
            ->workouts()
            ->with(['exercises' => function($query) {
                $query->select('exercises.*', 'workout_exercises.sets', 'workout_exercises.reps', 'workout_exercises.duration');
            }])
            ->get();

        return response()->json($workouts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:pull_day,push_day,leg_day',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'description' => 'nullable|string',
            'exercises' => 'required|array',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            'exercises.*.duration' => 'nullable|integer|min:1'
        ]);

        $workout = $request->user()->workouts()->create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'difficulty_level' => $validated['difficulty_level'],
            'description' => $validated['description']
        ]);

        foreach ($validated['exercises'] as $exercise) {
            $workout->exercises()->attach($exercise['exercise_id'], [
                'sets' => $exercise['sets'],
                'reps' => $exercise['reps'],
                'duration' => $exercise['duration'] ?? null
            ]);
        }

        return response()->json($workout->load('exercises'), 201);
    }

    public function show(Request $request, $id)
    {
        $workout = $request->user()->workouts()
            ->with(['exercises' => function($query) {
                $query->select('exercises.*', 'workout_exercises.sets', 'workout_exercises.reps', 'workout_exercises.duration');
            }])
            ->findOrFail($id);

        return response()->json($workout);
    }

    public function update(Request $request, $id)
    {
        $workout = $request->user()->workouts()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:pull_day,push_day,leg_day',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'description' => 'nullable|string',
            'exercises' => 'required|array',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            'exercises.*.duration' => 'nullable|integer|min:1'
        ]);

        $workout->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'difficulty_level' => $validated['difficulty_level'],
            'description' => $validated['description']
        ]);

        // Sync exercises dengan detach dan attach ulang
        $workout->exercises()->detach();
        
        foreach ($validated['exercises'] as $exercise) {
            $workout->exercises()->attach($exercise['exercise_id'], [
                'sets' => $exercise['sets'],
                'reps' => $exercise['reps'],
                'duration' => $exercise['duration'] ?? null
            ]);
        }

        return response()->json($workout->load('exercises'));
    }

    public function destroy(Request $request, $id)
    {
        $workout = $request->user()->workouts()->findOrFail($id);
        
        // Otomatis akan menghapus relasi di workout_exercises karena kita sudah set onDelete('cascade')
        $workout->delete();
        
        return response()->json([
            'message' => 'Workout deleted successfully'
        ], 200);
    }
}