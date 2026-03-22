<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Post extends Model
{
    use HasFactory;
    
    // Lista de atributos que permiten la asignación masiva desde formulario
    protected $fillable = ['body',];

    // Precarga automática (Eager Loading) del usuario en todas las consultas
    // Esto mitiga drásticamente el problema de rendimiendo "N+1 Queries"
    protected $with = ['user'];

    /**
     * Relación: Autoría (Cada Post es de exactamente un Usuario)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación Polimórfica: Likes (Un Post, al igual que un comentario,
     * puede recibir multitud de likes en la tabla polimórfica)
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Relación: Entidad 1-a-N para asociar directamente el Post con todos sus Comentarios.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }   
}
