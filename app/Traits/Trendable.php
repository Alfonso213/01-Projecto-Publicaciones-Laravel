<?php
namespace App\Traits;

trait Trendable
{
     /**
     * Scope para obtener los elementos más populares basados en 
     * likes recibidos en las últimas 24 horas.
     */
    public function scopePopularLast24Hours($query)
    {
        return $query->withCount(['likes' => function($q) {
            $q->where('created_at', '>=', now()->subDay());
        }])->orderByDesc('likes_count');
    }
    /**
     * Scope genérico para obtener populares en cualquier rango de tiempo.
     */
    public function scopePopularInLast($query, $hours = 24)
    {
        return $query->withCount(['likes' => function($q) use ($hours) {
            $q->where('created_at', '>=', now()->subHours($hours));
        }])->orderByDesc('likes_count');
    }
}
