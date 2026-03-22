<div x-data="{
         showShare: false,
         postUrl: '{{ url('/') }}/posts/{{ $post->id }}',
         copied: false,
         liked: {{ $post->likes->contains('user_id', auth()->id()) ? 'true' : 'false' }},
         likesCount: {{ $post->likes_count }},
         loading: false
     }"
     x-cloak
     class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 mb-6 transition-colors duration-300">
    
    <!-- CABECERA: Autor y Acciones rápidas -->
    <div class="flex justify-between items-start mb-4">
        <div>
            <a href="{{ route('users.show', $post->user->id) }}" 
               class="text-gray-900 dark:text-slate-100 font-bold hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-300 text-lg">
                {{ $post->user->name }}
            </a>
            <p class="text-gray-500 dark:text-slate-400 text-xs mt-0.5 transition-colors duration-300">
                {{ $post->created_at->diffForHumans() }}
            </p>
        </div>

        <div class="flex items-center space-x-2">
            <!-- Botón de Comentarios (Pill) -->
            <a href="{{ route('posts.show', $post->id) }}" 
               class="flex items-center space-x-1.5 p-2 rounded-lg text-gray-400 dark:text-slate-500 hover:bg-gray-100 dark:hover:bg-slate-800 transition-all">
                <i class="far fa-comment text-lg"></i>
                <span class="text-sm font-bold">{{ $post->comments_count }}</span>
            </a>

            <!-- Like -->
            <button @click="async () => {
                loading = true;
                try {
                    const response = await fetch('{{ route('posts.like', $post->id) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    const data = await response.json();
                    liked = data.liked;
                    likesCount = data.likesCount;
                } catch (error) { console.error('Error:', error); } finally { loading = false; }
            }"
            :disabled="loading"
            class="flex items-center space-x-1.5 p-2 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-slate-800"
            :class="liked ? 'bg-gray-100 dark:bg-slate-800' : 'text-gray-400 dark:text-slate-500'">
                <i class="fa-heart text-lg" :class="liked ? 'fas text-red-500' : 'far hover:text-red-500'"></i>
                <span class="text-sm font-bold" :class="liked ? 'text-gray-900 dark:text-slate-100' : 'hover:text-slate-300'" x-text="likesCount"></span>
            </button>

            <!-- Compartir -->
            <button @click="showShare = !showShare" class="p-2 rounded-lg text-gray-400 hover:bg-gray-100">
                <i class="fas fa-share-nodes text-lg"></i>
            </button>

            <!-- Eliminar -->
            @can('delete', $post)
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50"
                            onclick="return confirm('¿Eliminar publicación?')">
                        <i class="fas fa-trash-can"></i>
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <!-- CUERPO DEL POST -->
    <div class="text-gray-800 dark:text-slate-200 text-base leading-relaxed mb-6 transition-colors duration-300">
        {{ $post->body }}
    </div>

    <!-- SECCIÓN DE COMENTARIOS (Solo visible en vista detalle) -->
    @if(isset($showComments) && $showComments)
        <div class="mt-6 border-t border-gray-100 pt-4">
            @include('posts.inc.comments-form')
        </div>
    @endif

    <!-- MODAL DE COMPARTIR CON TELEPORT (Optimización de rendimiento) -->
    <template x-teleport="body">
        <div x-show="showShare" class="relative z-[100]">
            <!-- Fondo oscuro (Overlay) -->
            <div x-show="showShare"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/60 z-[100]"
                 @click="showShare = false"></div>

            <!-- Caja del Modal -->
            <div x-show="showShare"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm bg-white dark:bg-slate-900 rounded-2xl shadow-2xl z-[110] p-6 border border-gray-100 dark:border-slate-800">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Compartir publicación</h3>
                    <button @click="showShare = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Redes Sociales (FontAwesome) -->
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <button class="flex flex-col items-center space-y-2 group">
                        <div class="w-12 h-12 bg-[#1877F2] rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </div>
                        <span class="text-xs text-gray-600">Facebook</span>
                    </button>
                    <button class="flex flex-col items-center space-y-2 group">
                        <div class="w-12 h-12 bg-black rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fab fa-x-twitter text-xl"></i>
                        </div>
                        <span class="text-xs text-gray-600">X</span>
                    </button>
                    <button class="flex flex-col items-center space-y-2 group">
                        <div class="w-12 h-12 bg-[#25D366] rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fab fa-whatsapp text-2xl"></i>
                        </div>
                        <span class="text-xs text-gray-600">WhatsApp</span>
                    </button>
                    <button class="flex flex-col items-center space-y-2 group">
                        <div class="w-12 h-12 bg-[#0A66C2] rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </div>
                        <span class="text-xs text-gray-600">LinkedIn</span>
                    </button>
                </div>

                <!-- Copiar Enlace -->
                <div class="relative">
                    <input type="text" :value="postUrl" readonly 
                           class="w-full pl-4 pr-24 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none">
                    <button @click="navigator.clipboard.writeText(postUrl); copied = true; setTimeout(() => copied = false, 2000)"
                            class="absolute right-2 top-2 px-4 py-1.5 rounded-lg text-xs font-bold transition-all"
                            :class="copied ? 'bg-green-500 text-white' : 'bg-indigo-600 text-white hover:bg-indigo-700'">
                        <span x-text="copied ? '¡Hecho!' : 'Copiar'"></span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
