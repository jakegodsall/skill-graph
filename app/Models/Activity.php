<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'url',
        'status',
        'start_date',
        'end_date',
        'estimated_hours',
        'actual_hours',
        'metadata',
        'position_x',
        'position_y',
        'skill_id',
        'user_id',
    ];

    protected $casts = [
        'metadata' => 'json',
        'start_date' => 'date',
        'end_date' => 'date',
        'position_x' => 'decimal:2',
        'position_y' => 'decimal:2',
    ];

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dependencies(): HasMany
    {
        return $this->hasMany(ActivityDependency::class);
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(ActivityDependency::class, 'depends_on_activity_id');
    }

    // Activities this activity depends on
    public function dependsOn(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_dependencies', 'activity_id', 'depends_on_activity_id');
    }

    // Activities that depend on this activity
    public function requiredBy(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_dependencies', 'depends_on_activity_id', 'activity_id');
    }
}
