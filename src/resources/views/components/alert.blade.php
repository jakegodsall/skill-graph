@props(['type', 'icon', 'class'])

@php
    if(!isset($class)) $class = '';
    $class .= ' alert';
    if(in_array($type, ['default', 'success', 'warning', 'danger', 'info'])) $class .= ' alert-' . $type;
    $class = trim($class);
@endphp

<div class="{{ $class }}">
    @if($icon)
        <i class="fa-fw {{$icon}} mr-2"></i>
    @endif

    {{$slot}}
</div>
