<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts() {
        return $this->hasMany(Post::class);
    }

    
    public function commentLike() {
        return $this->hasMany(CommentLike::class);
    }

    public function subcomment() {
        return $this->hasMany(Subcomment::class);
    }

    
    public function postLike() {
        return $this->hasMany(Likes::class);
    }
    
    public function comments() {
        return $this->hasMany(Comments::class);
    }

    
    public function share () {
        return $this->belongsToMany(Share::class, 'share_user', 'user_id', 'share_id');
    }
    
    public function savedPost () {
        return $this->belongsToMany(Save::class, 'save_user', 'user_id', 'save_id');
    }

    public function friends () {
        return $this->belongsToMany(Friends::class, 'friends_user', 'user_id', 'friends_id');
    }

}
