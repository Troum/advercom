<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name', 'client', 'description', 'cover'
    ];
    protected $dates = [
        'created_at','updated_at'
    ];

    public function photos()
    {
        return $this->hasMany(ProjectPhotos::class);
    }

    public function videos()
    {
        return $this->hasMany(ProjectVideos::class);
    }
}
