<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityDependency;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ActivityController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Auth::user()->activities()
            ->with(['skill', 'dependsOn', 'requiredBy']);

        if ($request->has('skill_id')) {
            $query->where('skill_id', $request->skill_id);
        }

        $activities = $query->get();

        return response()->json($activities);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:course,project,book,practice,certification,other',
                'skill_id' => [
                    'required',
                    'integer',
                    'exists:skills,id,user_id,' . Auth::id()
                ],
                'url' => 'nullable|url',
                'status' => 'nullable|in:not_started,in_progress,completed,paused',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'estimated_hours' => 'nullable|integer|min:0',
                'actual_hours' => 'nullable|integer|min:0',
                'metadata' => 'nullable|json',
                'position_x' => 'nullable|numeric',
                'position_y' => 'nullable|numeric',
                'dependencies' => 'nullable|array',
                'dependencies.*' => [
                    'integer',
                    'exists:activities,id,user_id,' . Auth::id()
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Activity creation error:', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'message' => 'An error occurred while creating the activity',
                'error' => $e->getMessage()
            ], 500);
        }


        DB::transaction(function () use ($request, &$activity) {
            $activity = Auth::user()->activities()->create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'skill_id' => $request->skill_id,
                'url' => $request->url,
                'status' => $request->status ?? 'not_started',
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'estimated_hours' => $request->estimated_hours,
                'actual_hours' => $request->actual_hours,
                'metadata' => $request->metadata,
                'position_x' => $request->position_x,
                'position_y' => $request->position_y,
            ]);

            if ($request->has('dependencies')) {
                foreach ($request->dependencies as $dependencyId) {
                    $dependency = Activity::findOrFail($dependencyId);
                    if ($dependency->user_id === Auth::id()) {
                        ActivityDependency::create([
                            'activity_id' => $activity->id,
                            'depends_on_activity_id' => $dependencyId,
                        ]);
                    }
                }
            }
        });

        $activity->load(['skill', 'dependsOn', 'requiredBy']);

        return response()->json($activity, 201);
    }

    public function show(Activity $activity)
    {
        $this->authorize('view', $activity);

        $activity->load(['skill', 'dependsOn', 'requiredBy']);

        return response()->json($activity);
    }

    public function update(Request $request, Activity $activity)
    {
        $this->authorize('update', $activity);


        Log::info('working up to here');


        try {
            $request->validate([
                'name' => 'string|max:255',
                'description' => 'nullable|string',
                'type' => 'in:course,project,book,practice,certification,other',
                'skill_id' => [
                    'nullable',
                    'integer',
                    'exists:skills,id,user_id,' . Auth::id()
                ],
                'url' => 'nullable|url',
                'status' => 'in:not_started,in_progress,completed,paused',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'estimated_hours' => 'nullable|integer|min:0',
                'actual_hours' => 'nullable|integer|min:0',
                'metadata' => 'nullable|json',
                'position_x' => 'nullable|numeric',
                'position_y' => 'nullable|numeric',
                'dependencies' => 'nullable|array',
                'dependencies.*' => [
                    'integer',
                    'exists:activities,id,user_id,' . Auth::id()
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Activity update error:', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
                'activity_id' => $activity->id
            ]);
            return response()->json([
                'message' => 'An error occurred while updating the activity',
                'error' => $e->getMessage()
            ], 500);
        }

        DB::transaction(function () use ($request, $activity) {
            $activity->update($request->only([
                'name', 'description', 'type', 'skill_id', 'url', 'status',
                'start_date', 'end_date', 'estimated_hours', 'actual_hours',
                'metadata', 'position_x', 'position_y'
            ]));

            // Update dependencies if provided
            if ($request->has('dependencies')) {
                $activity->dependencies()->delete();
                
                foreach ($request->dependencies as $dependencyId) {
                    $dependency = Activity::findOrFail($dependencyId);
                    if ($dependency->user_id === Auth::id()) {
                        ActivityDependency::create([
                            'activity_id' => $activity->id,
                            'depends_on_activity_id' => $dependencyId,
                        ]);
                    }
                }
            }
        });

        $activity->load(['skill', 'dependsOn', 'requiredBy']);

        return response()->json($activity);
    }

    public function destroy(Activity $activity)
    {
        $this->authorize('delete', $activity);

        try {
            $activity->delete();
        } catch (\Exception $e) {
            \Log::error('Activity deletion error:', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'activity_id' => $activity->id
            ]);
            return response()->json([
                'message' => 'An error occurred while deleting the activity',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json(null, 204);
    }

    public function updatePosition(Request $request, Activity $activity)
    {
        $this->authorize('update', $activity);

        try {
            $request->validate([
                'position_x' => 'required|numeric',
                'position_y' => 'required|numeric',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Activity position update error:', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
                'activity_id' => $activity->id
            ]);
            return response()->json([
                'message' => 'An error occurred while updating the activity position',
                'error' => $e->getMessage()
            ], 500);
        }

        Log::info("position_x: {$request->position_x}, position_y: {$request->position_y}");

        $activity->update([
            'position_x' => $request->position_x,
            'position_y' => $request->position_y,
        ]);

        return response()->json($activity);
    }
}
