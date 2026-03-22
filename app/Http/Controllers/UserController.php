<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Muestra el perfil público de un usuario y todo su muro de publicaciones.
     * Carga todos los posts con su estadística (withCount) y autor (with)
     * para mantener el rendimiento y permitir la interactividad en la vista.
     */
    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user,
            'posts' => $user->posts()->with(['user'])->withCount(['likes', 'comments'])->latest()->paginate()
        ]);
    }
}
