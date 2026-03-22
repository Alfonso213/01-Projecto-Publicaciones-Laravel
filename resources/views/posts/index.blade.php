<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-slate-800">
                <div class="p-6 text-gray-900 dark:text-slate-100">

                @include('posts.inc.form')

                </div>
            </div>

            <div class="p-6">
            @include('posts.inc.list')
            </div>

        </div>
    </div>
</x-app-layout>