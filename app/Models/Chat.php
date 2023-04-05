<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    
    protected $fillable = ['receiver_id', 'sender_id', 'channel', 'message'];
    
    
    public function user () {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
