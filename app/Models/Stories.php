<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
    ];

    
    public function user() {
        return $this->belongsTo(User::class);
    }

    
    public function media () {
        return $this->belongsToMany(StoriesMedia::class, 'media_stories', 'stories_id', 'media_id');
    }

    
    public function text () {
        return $this->belongsToMany(StoriesText::class, 'stories_text', 'stories_id', 'text_id');
    }





}
