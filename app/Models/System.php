<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $table = 'systems';

    protected $guarded = [];


    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
