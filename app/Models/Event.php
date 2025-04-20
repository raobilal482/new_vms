<?php

namespace App\Models;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function manager(){
        return $this->belongsTo("App\Models\User")->where('type', UserTypeEnum::MANAGER->value);
    }

    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'event_volunteer', 'event_id', 'user_id')
            ->where('type', UserTypeEnum::VOLUNTEER->value);
    }

    // public function volunteers()
    // {
    //     return $this->hasMany(User::class)
    //         ->where('type', 'Volunteer');
    // }

    public function scopeWithVolunteers($query)
    {
        return $query->with(['volunteers' => fn ($q) => $q->where('type', UserTypeEnum::VOLUNTEER->value)]);
    }
    public function event_organizer(){
        return $this->belongsTo("App\Models\User")->where('type', UserTypeEnum::EVENT_ORGANIZER->value);
    }
    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'event_id');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'event_id');
    }
}
