<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'team_id',

    ];


    public function getDashboardRoute()
    {
        return match (true) {
            $this->hasRole('admin') => 'admin/dashboard',
            $this->hasRole('leader') => 'leader/dashboard',
            $this->hasRole('engineer') => 'engineer',
            default => '/',
        };
    }

    protected static function booted()
    {
        static::saved(function ($user) {
            if ($user->role && !$user->hasRole($user->role)) {
                $user->syncRoles([$user->role]);
            }
        });
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    public function tasksAssigned()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function tasksCreated()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'engineer_skills')->withPivot('level')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->user_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'priority' => $this->priority,
            'link' => $this->link,
            'status' => $this->status,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }

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
}
