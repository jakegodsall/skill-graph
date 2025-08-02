@props(['type'])

@php
    $class = 'pill';
    if(in_array($type, ['success', 'warning', 'danger', 'primary', 'secondary', 'grey'])) $class .= ' pill-' . $type;

@endphp

<div class="{{ $class }}" {!! $attributes !!}>{{$slot}}</div>
