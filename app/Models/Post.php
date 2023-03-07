<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
        'public'
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function images () {
        return $this->belongsToMany(Media::class, 'media_post', 'post_id', 'media_id');
    }

    
    public function likes () {
        return $this->belongsToMany(Likes::class, 'likes_post', 'post_id', 'likes_id');
    }

    
    public function comments () {
        return $this->belongsToMany(Comments::class, 'comments_post', 'post_id', 'comments_id');
    }

    public function share () {
        return $this->hasMany(Share::class);
    }
}
