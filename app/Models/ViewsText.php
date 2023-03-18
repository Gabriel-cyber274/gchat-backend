<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewsText extends Model
{
    use HasFactory;

    protected $fillable = ['text_id', 'user_id'];


    
    public function media() {
        return $this->belongsTo(StoriesText::class);
    }


    public function user () {
        return $this->belongsToMany(User::class, 'user_viewtext', 'viewtext_id', 'user_id');
    }

}
