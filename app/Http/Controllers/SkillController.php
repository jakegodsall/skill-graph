<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $skill->update($request->only(['name', 'description', 'color']));

        return response()->json($skill);
    }

    public function destroy(Skill $skill)
    {
        $this->authorize('delete', $skill);
        
        $skill->delete();

        return response()->json(null, 204);
    }
}
