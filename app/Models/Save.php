<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Save extends Model
{
    use HasFactory;

    protected $fillable=['user_id','post_id'];

    
    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user () {
        return $this->belongsToMany(User::class, 'save_user', 'save_id', 'user_id');
    }

}
