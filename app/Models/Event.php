<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function manager(){
        return $this->belongsTo("App\Models\User")->where('type', 'manager');
    }
    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'event_volunteer', 'event_id', 'user_id')
            ->where('users.type', 'volunteer');
    }
    public function event_organizer(){
        return $this->belongsTo("App\Models\User")->where('type', 'event organizer');
    }

}
