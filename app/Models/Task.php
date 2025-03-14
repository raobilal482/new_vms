<?php

namespace App\Models;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'task_volunteer', 'task_id', 'volunteer_id')
        ->where('type', UserTypeEnum::VOLUNTEER->value);
    }
    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'task_id');
    }
}
