<div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-4">
    <div class="flex justify-between items-start">
        <div>
            <a href="{{ route('users.show', $post->user->id) }}" 
               class="text-gray-900 font-bold hover:text-indigo-600 transition-colors duration-200">
                {{ $post->user->name }}
            </a>
            <p class="text-gray-500 text-xs mt-0.5">
                {{ $post->created_at->diffForHumans() }}
            </p>
        </div>

        <div class="flex items-center space-x-2">
            <button type="button" 
                class="text-gray-400 hover:text-indigo-600 p-1 rounded-full hover:bg-gray-50 transition-all duration-200"
                title="Compartir">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M13 4.5a2.5 2.5 0 1 1 .702 1.737L6.97 9.604a2.518 2.518 0 0 1 0 .792l6.733 3.367a2.5 2.5 0 1 1-.671 1.341l-6.733-3.367a2.5 2.5 0 1 1 0-3.475l6.733-3.366A2.52 2.52 0 0 1 13 4.5Z" />
                </svg>
            </button>

            @can('delete', $post)
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="text-red-500 hover:bg-red-50 px-2 py-1 rounded-md text-xs font-medium transition-all"
                            onclick="return confirm('¿Estás seguro?')">
                        {{ __('Delete') }}
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <div class="mt-3 text-gray-700 leading-relaxed text-sm">
        {{ $post->body }}
    </div>
</div>