<?php

namespace App\Models;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Event extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);
    }
    // public function images(): HasMany
    // {
    //     return $this->hasMany(Media::class, 'model_id', 'id')->where([
    //         'collection_name' => 'images',
    //         'model_type' => 'App\Models\Event',
    //     ]);
    // }

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
