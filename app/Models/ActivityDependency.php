<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityDependency extends Model
{
    protected $fillable = [
        'activity_id',
        'depends_on_activity_id',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function dependsOnActivity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'depends_on_activity_id');
    }
}
