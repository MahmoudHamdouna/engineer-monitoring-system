<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $guarded = [];

     public function engineers()
    {
        return $this->belongsToMany(User::class, 'engineer_skills')->withPivot('level')->withTimestamps();
    }
}
