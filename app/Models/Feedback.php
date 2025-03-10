<?php

namespace App\Models;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $guarded = ['id'];

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
    public function volunteer()
    {
        return $this->belongsTo(User::class, 'receiver_id')
            ->where('type', UserTypeEnum::VOLUNTEER->value);
    }
    public function organizer()
    {
        return $this->belongsTo(User::class, 'receiver_id')

        ->where('type', UserTypeEnum::EVENT_ORGANIZER->value);
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'receiver_id')

        ->where('type', UserTypeEnum::MANAGER->value);
    }
    public function giver()
    {
        return $this->belongsTo(User::class, 'giver_id');
    }
}
