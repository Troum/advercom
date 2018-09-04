<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectVideos extends Model
{
    protected $fillable = [
      'project_id', 'video'
    ];

    public function projectVideo()
    {
        return $this->belongsTo(Project::class);
    }
}
