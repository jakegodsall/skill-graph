<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SkillController extends Controller
{
    // Authentication handled by web middleware group

    public function index()
    {
        $skills = Auth::user()->skills()->withCount('activities')->get();
        
        return response()->json($skills);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('skills')->where(function ($query) use ($request) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
            'description' => 'nullable|string',
            'color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        $skill = Auth::user()->skills()->create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? '#3B82F6',
        ]);

        return response()->json($skill, 201);
    }

    public function show(Skill $skill)
    {
        $this->authorize('view', $skill);
        
        $skill->load(['activities' => function ($query) {
            $query->with(['dependsOn', 'requiredBy']);
        }]);

        return response()->json($skill);
    }

    public function update(Request $request, Skill $skill)
    {
        $this->authorize('update', $skill);

        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('skills')->where(function ($query) use ($request) {
                        return $query->where('user_id', Auth::id());
                    })->ignore($skill->id),
                ],
                'description' => 'nullable|string',
                'color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Skill update error:', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
                'skill_id' => $skill->id
            ]);
            return response()->json([
                'message' => 'An error occurred while updating the skill',
                'error' => $e->getMessage()
            ], 500);
        }

        $skill->update($request->only(['name', 'description', 'color']));

        return response()->json($skill);
    }

    public function destroy(Skill $skill)
    {
        $this->authorize('delete', $skill);
        
        try {
            $skill->delete();
        } catch (\Exception $e) {
            \Log::error('Skill deletion error:', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'skill_id' => $skill->id
            ]);
            return response()->json([
                'message' => 'An error occurred while deleting the skill',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json(null, 204);
    }
}
