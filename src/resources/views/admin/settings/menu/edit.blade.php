<x-app-layout>

    <x-slot name="title">{{ __('Edit :name', ['name' => $menuitem->title]) }}</x-slot>

    <x-slot name="header">
        <h1><a href="{{ route('admin.settings.index') }}">{{ __('Settings') }}</a> &gt; <a href="{{ route('admin.settings.menu.index') }}">{{ __('Menu') }}</a> &gt; {{ $menuitem->title }}</h1>
    </x-slot>

    <form method="post" action="{{ route('admin.settings.menu.update', $menuitem) }}">
        @csrf
        @method('PATCH')
        @include('admin.settings.menu.partial_form')
    </form>

    @can('admin delete')
    <x-danger-button class="mt-2" onclick="event.preventDefault(); if(confirm('{{ __('Are you sure you want to delete :name?', ['name' => $menuitem->title]) }}')) document.getElementById('delete-form').submit();">
        <i class="fas fa-fw fa-trash mr-2"></i> {{ __('Delete Menu Item') }}
    </x-danger-button>
    <form id="delete-form" class="hidden" method="post" action="{{ route('admin.settings.menu.destroy', $menuitem) }}">
        @csrf
        @method('DELETE')
    </form>
    @endcan

</x-app-layout>
