<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoriesMedia extends Model
{
    use HasFactory;

    
    protected $fillable = ['file', 'story_id', 'user_id'];

    
    public function stories () {
        return $this->belongsToMany(Stories::class, 'media_stories', 'media_id', 'stories_id');
    }

    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function views() {
        return $this->hasMany(Views::class, 'media_id', 'id');
    }

    
}
