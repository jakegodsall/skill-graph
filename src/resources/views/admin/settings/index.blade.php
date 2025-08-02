<x-app-layout>

    <x-slot name="title">{{ __('Settings') }}</x-slot>

    <x-slot name="header">
        <h1>{{ __('Settings') }}</h1>
    </x-slot>

    @canany(['admin manage notifications', 'admin manage search synonyms'])
        <h2 class="h2 mb-5">{{ __('Notifications') }}</h2>

        <div class="grid lg:grid-cols-2 gap-6 mb-6">

            @can('admin manage notifications')
                <div class="px-4 py-5 bg-gray-50 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    <h2 class="text-lg text-primary font-medium mb-2">{{ __('Notifications') }}</h2>
                    <p class="card-text">{{ __('Manage notifications for users and admins.') }}</p>
                    <a href="{{ route('admin.settings.notifications.index') }}" class="btn btn-primary mt-2">{{ __('Manage') }}</a>
                </div>
            @endcan

            @can('admin manage synonyms')
                <div class="px-4 py-5 bg-gray-50 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    <h2 class="text-lg text-primary font-medium mb-2">{{ __('Search Synonyms') }}</h2>
                    <p class="card-text">{{ __('Set synonyms for search i.e. hat = cap') }}</p>
                    <a href="{{ route('admin.settings.search-synonyms.index') }}" class="btn btn-primary mt-2">{{ __('Manage') }}</a>
                </div>
            @endcan

        </div>


    @endcan


    @can('admin manage users and roles')
        <h2 class="h2 mb-5">{{ __('Users & Roles') }}</h2>

        <div class="grid lg:grid-cols-2 gap-6 mb-6">

            <div class="px-4 py-5 bg-gray-50 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                <h2 class="text-lg text-primary font-medium mb-2">{{ __('Users') }}</h2>
                <p class="card-text">{{ __('Manage users and their settings.') }}</p>
                <a href="{{ route('admin.settings.users.index') }}" class="btn btn-primary mt-2">{{ __('Manage') }}</a>
            </div>

            <div class="px-4 py-5 bg-gray-50 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                <h2 class="text-lg text-primary font-medium mb-2">{{ __('Roles') }}</h2>
                <p class="card-text">{{ __('Manage roles and permissions') }}</p>
                <a href="{{ route('admin.settings.roles.index') }}" class="btn btn-primary mt-2">{{ __('Manage') }}</a>
            </div>
        </div>
    @endcan

    @canany(['admin view audit log', 'admin manage menu'])
    <h2 class="h2 mb-5">{{ __('Advanced') }}</h2>

    <div class="grid lg:grid-cols-2 gap-6 mb-6">

        @can('admin view audit log')
            <div class="px-4 py-5 bg-gray-50 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                <h2 class="text-lg text-primary font-medium mb-2">{{ __('Audit Log') }}</h2>
                <p class="card-text">{{ __('See audit trail on key actions within the system') }}</p>
                <a href="{{ route('admin.settings.audit-log.index') }}" class="btn btn-primary mt-2">{{ __('View Log') }}</a>
            </div>
        @endcan

        @can('admin manage menu')
            <div class="px-4 py-5 bg-gray-50 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                <h2 class="text-lg text-primary font-medium mb-2">{{ __('Menu') }}</h2>
                <p class="card-text">{{ __('Configure the sidebar menu for admin and users') }}</p>
                <a href="{{ route('admin.settings.menu.index') }}" class="btn btn-primary mt-2">{{ __('Manage') }}</a>
            </div>
        @endcan

        @can('admin')
            <div class="px-4 py-5 bg-gray-50 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                <h2 class="text-lg text-primary font-medium mb-2">{{ __('About') }}</h2>
                <p class="card-text">{{ __('See application details and more') }}</p>
                <a href="{{ route('admin.settings.about.index') }}" class="btn btn-primary mt-2">{{ __('View') }}</a>
            </div>
        @endcan

    </div>
    @endcanany

</x-app-layout>
