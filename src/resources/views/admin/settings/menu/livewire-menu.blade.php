<div>
    <div class="tabs">
        @foreach($tabs as $tab => $text)
            <button type="button" class="tab {{ $current_tab == $tab || $current_tab == '' ? 'active' : '' }}" wire:click="$set('current_tab', '{{$tab}}')">{{ $text }}</button>
        @endforeach
    </div>

    <div id="sortable-menu" class="dd">
        <ol class="dd-list">
            @include('admin.settings.menu.loop', ['rows' => $menuitems])
        </ol>
    </div>
</div>
