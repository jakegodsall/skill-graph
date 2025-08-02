<x-app-layout>
    <x-slot name="title">{{ __('Dashboard') }}</x-slot>

    <x-slot name="header">
        <h1>{{ __('Dashboard') }}</h1>
    </x-slot>

    <p>Dashboard</p>

    <a href="{{ route('admin.user.index') }}">Users</a>

</x-app-layout>
