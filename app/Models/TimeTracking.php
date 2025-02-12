<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeTracking extends Model
{

    public $guarded = ['id'];
    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    // Correct Relationship for a Single Task
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
