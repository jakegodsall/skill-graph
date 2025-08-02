@props(['rows'])

@foreach($rows as $item)
@unless($item['dropdownOnly'] && !$item['hasChildren'])
<li x-data="{ open: {{ $item['on'] ? 'true' : 'false' }} }">
    <div class="flex nav-link{{ $item['on'] ? ' active' : '' }}">
        <a class="grow" href="{{ $item['url'] }}" @if(!$item['internal']) target="_blank" @endif @if($item['dropdownOnly']) data-toggle="collapse" data-target="#nav-{{$item['id']}}" @endif >
        <i class="fa-fw {{ $item['icon'] ?? 'folder' }} mr-2"></i> <span>{{ $item['title'] }}</span>
        </a>
        @if(count($item['children']))
        <button @click="open = ! open" class="w-8 flex items-center justify-center">
            <i :class="{'fas fa-chevron-circle-up text-blue': open, 'far fa-chevron-circle-down': ! open }"></i>
        </button>
        @endif
    </div>

    @if(count($item['children']))
    <ul :class="{'block': open, 'hidden': ! open}" id="nav-{{ $item['id'] }}" class="pl-6 hidden">
        <x-menu :rows="$item['children']" />
    </ul>
    @endif
</li>
@endunless
@endforeach
