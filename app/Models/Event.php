<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function manager(){
        return $this->belongsTo("App\Models\User")->where('type', 'Manager');
    }

    public function event_volunteers()
    {
        return $this->belongsToMany(User::class, 'event_volunteer', 'event_id', 'user_id')
            ->where('type', 'Volunteer');
    }

    public function volunteers()
    {
        return $this->hasMany(User::class)
            ->where('type', 'Volunteer');
    }

    public function scopeWithVolunteers($query)
    {
        return $query->with(['volunteers' => fn ($q) => $q->where('type', 'Volunteer')]);
    }
    public function event_organizer(){
        return $this->belongsTo("App\Models\User")->where('type', 'Event Organizer');
    }
    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'event_id');
    }
}
