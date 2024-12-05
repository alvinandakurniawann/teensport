<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $schedules = $request->user()
            ->schedules()
            ->with(['workout' => function($query) {
                $query->with(['exercises' => function($query) {
                    $query->select('exercises.*', 'workout_exercises.sets', 'workout_exercises.reps', 'workout_exercises.duration');
                }]);
            }])
            ->get();

        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'workout_id' => 'required|exists:workouts,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'is_completed' => 'boolean'
        ]);

        $schedule = $request->user()->schedules()->create([
            'workout_id' => $validated['workout_id'],
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'] ?? null,
            'is_completed' => $validated['is_completed'] ?? false
        ]);

        return response()->json($schedule->load(['workout.exercises']), 201);
    }

    public function show(Request $request, $id)
    {
        $schedule = $request->user()->schedules()
            ->with(['workout' => function($query) {
                $query->with(['exercises' => function($query) {
                    $query->select('exercises.*', 'workout_exercises.sets', 'workout_exercises.reps', 'workout_exercises.duration');
                }]);
            }])
            ->findOrFail($id);

        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {
        $schedule = $request->user()->schedules()->findOrFail($id);

        $validated = $request->validate([
            'workout_id' => 'required|exists:workouts,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'is_completed' => 'boolean'
        ]);

        $schedule->update([
            'workout_id' => $validated['workout_id'],
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'] ?? null,
            'is_completed' => $validated['is_completed'] ?? false
        ]);

        return response()->json($schedule->load(['workout.exercises']));
    }

    public function destroy(Request $request, $id)
    {
        $schedule = $request->user()->schedules()->findOrFail($id);
        $schedule->delete();
        
        return response()->json([
            'message' => 'Schedule deleted successfully'
        ]);
    }

    public function markAsComplete(Request $request, $id)
    {
        $schedule = $request->user()->schedules()->findOrFail($id);
        $schedule->update([
            'is_completed' => true
        ]);

        return response()->json([
            'message' => 'Schedule marked as completed',
            'schedule' => $schedule->load(['workout.exercises'])
        ]);
    }

    // Tambahan method untuk mendapatkan jadwal mingguan
    public function weekly(Request $request)
    {
        $schedules = $request->user()
            ->schedules()
            ->with(['workout' => function($query) {
                $query->with(['exercises' => function($query) {
                    $query->select('exercises.*', 'workout_exercises.sets', 'workout_exercises.reps', 'workout_exercises.duration');
                }]);
            }])
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return response()->json($schedules);
    }
}