<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectPhotos extends Model
{
    protected $fillable = [
        'project_id', 'photo'
    ];

    public function projectPhoto()
    {
        return $this->belongsTo(Project::class);
    }
}
