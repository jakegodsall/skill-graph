@foreach($rows as $item)
    <li class="dd-item" data-id="{{ $item['id'] }}">
        <div class="dd-handle"><i class="fa-fw fas fa-bars"></i></div>
        <div class="dd-content pr-3">
            <b><a href="{{ route('admin.settings.menu.edit', $item['id']) }}">{{ $item['title'] }}</a></b>
            <a href="{{ route('admin.settings.menu.edit', $item['id']) }}" class="hidden lg:block edit-menu-item float-right btn btn-sm btn-primary"><i class="fa-fw fas fa-edit"></i></a>
        </div>

        @if(count($item['children']))
            <ol class="dd-list">
                @include('admin.settings.menu.loop', ['rows' => $item['children']])
            </ol>
        @endif
    </li>
@endforeach
