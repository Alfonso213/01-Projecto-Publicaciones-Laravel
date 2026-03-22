<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Volver al muro</span>
            </a>
        </div>

        {{-- Pasamos la variable showComments=true para forzar que el item muestre el formulario --}}
        @include('posts.inc.item', ['post' => $post, 'showComments' => true])
    </div>
</x-app-layout>
