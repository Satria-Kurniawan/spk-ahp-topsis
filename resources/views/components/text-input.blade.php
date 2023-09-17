@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'border-gray-300 focus:border-gray-800 focus:ring-gray-800 rounded-md shadow-sm',
]) !!}>
