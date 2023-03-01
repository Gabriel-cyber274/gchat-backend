<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcomment extends Model
{
    use HasFactory;

    protected $fillable = ['comment_id', 'user_id', 'comment'];

    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function comment() {
        return $this->belongsToMany(Comments::class, 'comment_subcomment', 'subcomment_id', 'comment_id');
    }
}
