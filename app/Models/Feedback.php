<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $guarded = ['id'];

    public function giver()
    {
        return $this->belongsTo(User::class, 'giver_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
