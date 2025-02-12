<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'task_volunteer', 'task_id', 'volunteer_id');
    }
    
}
