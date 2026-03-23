<?php

namespace App\Models;
use App\Traits\Likeable;
use App\Traits\Trendable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Post extends Model
{
    use HasFactory, Likeable, Trendable;
    
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
     * Relación: Entidad 1-a-N para asociar directamente el Post con todos sus Comentarios.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }   
}
