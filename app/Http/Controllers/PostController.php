<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
   use AuthorizesRequests;
   public function index()
   {
      return view('posts.index', [
         'posts' => Post::latest()->paginate(),
      ]);
   }

   public function store(Request $request)
   {
      $request->validate(['body' => 'required']);

      //dd($request->only('body'));

      $request->user()->posts()->create($request->only('body'));
      return back()->with('status', 'Publicacion guardada exitosamente');
   }

   public function destroy(Request $request, Post $post)
{
    // Esto te dirá si el objeto $post tiene datos o está vacío
    //dd($post); 

    // Esto te dirá si los IDs coinciden
    //dd($request->user()->id, $post->user_id);

    \Illuminate\Support\Facades\Gate::authorize('delete', $post);
    $post->delete();
    return back();
}
}
