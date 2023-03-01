<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    
    protected $fillable = ['comment', 'post_id', 'user_id'];

    
    public function posts () {
        return $this->belongsToMany(Post::class, 'comments_post', 'comments_id', 'post_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function commentlike() {
        return $this->belongsToMany(CommentLike::class, 'commentlike_comment', 'comment_id', 'commentlike_id');
    }

    
    public function subcomments() {
        return $this->belongsToMany(Subcomment::class, 'comment_subcomment', 'comment_id', 'subcomment_id');
    }
    
}
