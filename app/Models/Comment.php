<?php

namespace App\Models;
use App\Traits\Likeable;
use App\Traits\Trendable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 


class Comment extends Model
{   
    use HasFactory, Likeable, Trendable;
    protected $fillable = ['body', 'post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
