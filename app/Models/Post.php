<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['body',];

    protected $with = ['user'];

    public function user()
    {
        return $this-> belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }   
}
