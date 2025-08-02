<x-app-layout>

    <x-slot name="title">{{ __('Create Menu Item') }}</x-slot>

    <x-slot name="header">
        <h1><a href="{{ route('admin.settings.index') }}">{{ __('Settings') }}</a> &gt; <a href="{{ route('admin.settings.menu.index') }}">{{ __('Menu') }}</a> &gt; {{ __('Create New') }}</h1>
    </x-slot>

    <form method="post" action="{{ route('admin.settings.menu.store') }}">
        @csrf
        @include('admin.settings.menu.partial_form')
    </form>

</x-app-layout>
