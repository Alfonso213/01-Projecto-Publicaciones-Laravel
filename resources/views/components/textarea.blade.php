@props(['value'])

<textarea 
    rows="3"
    {{ $attributes->merge([
        'class' => 'block w-full border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm'
    ]) }}
>{{ $value ?? $slot }}</textarea>