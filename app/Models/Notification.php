<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'image',
        'page_id',
        'user_id',
        'name',
        'read'
    ];


    public function user () {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id');
    }
}
