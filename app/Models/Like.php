<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    // Ahora permitimos los campos polimórficos y eliminamos post_id
    protected $fillable = ['user_id', 'likeable_id', 'likeable_type'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Definición correcta de la relación polimórfica
    public function likeable() 
    { 
        return $this->morphTo(); 
    }

    // Acceso directo al Post si el likeable es un Post
    public function post()
    {
        return $this->belongsTo(Post::class, 'likeable_id')->where('likeable_type', Post::class);
    }
}
