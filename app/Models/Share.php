<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;

    protected $fillable=['user_id','post_id', 'public'];

    
    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user () {
        return $this->belongsToMany(User::class, 'share_user', 'share_id', 'user_id');
    }

}
