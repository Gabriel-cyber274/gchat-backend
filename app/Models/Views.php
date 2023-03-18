<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Views extends Model
{
    use HasFactory;

    protected $fillable = ['media_id', 'user_id'];


    
    public function media() {
        return $this->belongsTo(StoriesMedia::class);
    }


    public function user () {
        return $this->belongsToMany(User::class, 'user_view', 'view_id', 'user_id');
    }

}
