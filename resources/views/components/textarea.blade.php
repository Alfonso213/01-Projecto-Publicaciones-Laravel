@props(['value'])

<textarea 
    rows="3"
    {{ $attributes->merge([
        'class' => 'block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm'
    ]) }}
>{{ $value ?? $slot }}</textarea>