<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoriesText extends Model
{
    use HasFactory;

    
    protected $fillable = ['text', 'story_id', 'user_id'];

    
    public function stories () {
        return $this->belongsToMany(Stories::class, 'stories_text', 'text_id', 'stories_id');
    }

    
    public function user() {
        return $this->belongsTo(User::class);
    }

    
    public function views() {
        return $this->hasMany(ViewsText::class, 'text_id', 'id');
    }


}
