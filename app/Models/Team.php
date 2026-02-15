<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $guarded = [];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'team_id');
    }

    public function tasks()
    {
        return $this->hasManyThrough(
            Task::class,
            Project::class,
            'team_id',    // FK في projects
            'project_id', // FK في tasks
            'id',
            'id'
        );
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
