@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control w-full']) !!}>{{trim($slot)}}</textarea>
