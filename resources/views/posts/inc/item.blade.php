<div x-data="{
         showShare: false,
         postUrl: '{{ url('/') }}/posts/{{ $post->id }}',
         copied: false
     }"
     x-cloak
     @load="showShare = false">
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

        <div class="flex items-center space-x-1">
            <!-- Botón de Like -->
            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="flex items-center space-x-1 p-2 rounded-full transition-all duration-200
                                {{ $post->likes->contains('user_id', auth()->id()) 
                                    ? 'text-red-500 hover:bg-red-100' 
                                    : 'text-gray-400 hover:text-red-500 hover:bg-gray-100' }}"
                        title="Me gusta">
                    <i class="fa-heart {{ $post->likes->contains('user_id', auth()->id()) ? 'fas' : 'far' }}"></i>
                    <span class="text-xs font-semibold">{{ $post->likes->count() }}</span>
                </button>
            </form>

            <!-- Botón de Compartir -->
            <button type="button" 
                @click="showShare = !showShare"
                class="p-2 rounded-full text-gray-400 hover:text-indigo-600 hover:bg-indigo-100 transition-all duration-200"
                title="Compartir">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M13 4.5a2.5 2.5 0 1 1 .702 1.737L6.97 9.604a2.518 2.518 0 0 1 0 .792l6.733 3.367a2.5 2.5 0 1 1-.671 1.341l-6.733-3.367a2.5 2.5 0 1 1 0-3.475l6.733-3.366A2.52 2.52 0 0 1 13 4.5Z" />
                </svg>
            </button>

            <!-- Botón de Eliminar -->
            @can('delete', $post)
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="p-2 rounded-full text-gray-400 hover:text-red-600 hover:bg-red-100 transition-all duration-200"
                            title="Eliminar"
                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta publicación?')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.006c0 .414.336.75.75.75h.006a.75.75 0 00-.756.75v10.5a2.75 2.75 0 002.75 2.75h5.5a2.75 2.75 0 002.75-2.75V5.5a.75.75 0 00-.756-.75h.006a.75.75 0 00.75-.75v-.006A2.75 2.75 0 0011.25 1h-2.5zM4.75 5.5a.75.75 0 00-.75.75v10.5c0 .966.784 1.75 1.75 1.75h5.5a1.75 1.75 0 001.75-1.75V6.25a.75.75 0 00-.75-.75h-8zM7.5 8a.75.75 0 01.75.75v5.5a.75.75 0 01-1.5 0v-5.5A.75.75 0 017.5 8zm3.5 0a.75.75 0 01.75.75v5.5a.75.75 0 01-1.5 0v-5.5A.75.75 0 0111 8z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <div class="mt-3 text-gray-700 leading-relaxed text-sm">
        {{ $post->body }}
    </div>
    </div>

    <!-- Overlay de fondo difuminado -->
    <div x-show="showShare"
         @click="showShare = false"
         x-transition
         style="display: none;"
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-30"></div>

    <!-- Modal de compartir -->
    <div x-show="showShare" 
         @click.outside="showShare = false"
         x-transition.scale.90
         style="display: none;"
         class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 bg-white rounded-lg shadow-2xl border border-gray-200 z-50 p-4">
        
        <!-- Encabezado -->
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-gray-900">Compartir publicación</h3>
            <p class="text-xs text-gray-500 mt-1">Selecciona dónde deseas compartir</p>
        </div>

        <!-- Iconos de redes sociales -->
        <div class="grid grid-cols-5 gap-3 mb-4 pb-4 border-b border-gray-200">
            <!-- Facebook -->
            <button type="button"
                    class="flex flex-col items-center space-y-1 p-2 rounded-lg hover:bg-gray-100 transition-colors group"
                    title="Compartir en Facebook">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:shadow-md transition-shadow">
                    <i class="fab fa-facebook-f text-white text-lg"></i>
                </div>
                <span class="text-xs text-gray-600">Facebook</span>
            </button>

            <!-- Twitter/X -->
            <button type="button"
                    class="flex flex-col items-center space-y-1 p-2 rounded-lg hover:bg-gray-100 transition-colors group"
                    title="Compartir en X (Twitter)">
                <div class="w-10 h-10 bg-black rounded-lg flex items-center justify-center group-hover:shadow-md transition-shadow">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24h-6.637l-5.206-6.807-5.979 6.807H2.456l7.746-8.973L.77 2.25h6.82l4.711 6.231 5.243-6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </div>
                <span class="text-xs text-gray-600">X</span>
            </button>

            <!-- WhatsApp -->
            <button type="button"
                    class="flex flex-col items-center space-y-1 p-2 rounded-lg hover:bg-gray-100 transition-colors group"
                    title="Compartir en WhatsApp">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center group-hover:shadow-md transition-shadow">
                    <i class="fa-brands fa-whatsapp text-white text-lg"></i>
                </div>
                <span class="text-xs text-gray-600">WhatsApp</span>
            </button>

            <!-- LinkedIn -->
            <button type="button"
                    class="flex flex-col items-center space-y-1 p-2 rounded-lg hover:bg-gray-100 transition-colors group"
                    title="Compartir en LinkedIn">
                <div class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center group-hover:shadow-md transition-shadow">
                    <i class="fa-brands fa-linkedin text-white text-lg"></i>
                </div>
                <span class="text-xs text-gray-600">LinkedIn</span>
            </button>

            <!-- Email -->
            <button type="button"
                    class="flex flex-col items-center space-y-1 p-2 rounded-lg hover:bg-gray-100 transition-colors group"
                    title="Compartir por Email">
                <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center group-hover:shadow-md transition-shadow">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                </div>
                <span class="text-xs text-gray-600">Email</span>
            </button>
        </div>

        <!-- Barra de copia de enlace -->
        <div class="space-y-2">
            <label class="text-xs font-medium text-gray-700">Copiar enlace</label>
            <div class="flex items-center space-x-2">
                <input type="text" 
                       :value="postUrl"
                       readonly
                       class="flex-1 px-3 py-2 text-xs border border-gray-300 rounded-lg bg-gray-50 text-gray-600 focus:outline-none select-all">
                <button type="button"
                        @click="navigator.clipboard.writeText(postUrl); copied = true; setTimeout(() => copied = false, 2000)"
                        :class="copied ? 'bg-green-500 text-white' : 'bg-indigo-600 hover:bg-indigo-700 text-white'"
                        class="px-3 py-2 rounded-lg text-xs font-medium transition-all duration-300">
                    <span x-show="!copied">Copiar</span>
                    <span x-show="copied" class="flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>¡Copiado!</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>