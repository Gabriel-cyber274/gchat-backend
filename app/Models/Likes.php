<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    use HasFactory;

    protected $fillable = ['like'];
    
    public function posts () {
        return $this->belongsToMany(Post::class, 'likes_post', 'likes_id', 'post_id');
    }
}
