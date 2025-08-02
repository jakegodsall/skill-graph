@props([
    'type' => 'text',
    'disabled' => false
])

<input type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'text-sm w-full rounded-lg outline-none bg-tertiary-grey border-2 border-secondary-grey shadow-md']) !!}>
