<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $protected = [
        'id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function givenFeedback()
    {
        return $this->hasMany(Feedback::class, 'giver_id');
    }

    // Feedback received by this user
    public function receivedFeedback()
    {
        return $this->hasMany(Feedback::class, 'receiver_id');
    }
    public function totalEventHours()
{
    return $this->hasMany(TimeTracking::class)
        ->selectRaw('SUM(TIMESTAMPDIFF(HOUR, checkin_time, checkout_time)) as total_hours')
        ->pluck('total_hours')
        ->first() ?? 0;
}
public function totalTaskHours()
{
    return TimeTracking::whereIn('event_id', $this->events()->pluck('id'))
        ->sum('hours_logged');
}
public function getFilamentAvatarUrl(): ?string
{
    $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
    return $this->$avatarColumn ? Storage::url("$this->$avatarColumn") : null;
}
}
