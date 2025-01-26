<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    public function event_type(){
        return $this->belongsTo("App\Models\Type");
    }

    public function manager(){
        return $this->belongsTo("App\Models\User")->where('type', 'Manager');
    }
}
