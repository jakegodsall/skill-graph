<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($adminView) && $adminView ? __('Edit User Profile') : __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div 
                    class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4 px-4 py-3 rounded-lg bg-green-100 border border-green-400 text-green-700"
                    role="alert"
                >
                    @if (session('status') === 'password-updated')
                        Password was successfully updated.
                    @elseif (session('status') === 'profile-updated' && session()->has('email-sent'))
                        {{ session('email-sent') }}
                    @elseif (session('status') === 'profile-updated')
                        Profile was successfully updated.
                    @else
                        {{ session('status') }}
                    @endif
                </div>
            @endif
             
            @if ($errors->any())
                <div 
                    class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4 px-4 py-3 rounded-lg bg-red-100 border border-red-400 text-red-700"
                    role="alert"
                >
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form', ['user' => $user])
                </div>
            </div>

            @if (!isset($adminView) || !$adminView)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <livewire:admin.profile.edit-roles :user="$user"/>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <livewire:auth.two-factor-authentication />
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                       {{-- @include('profile.partials.delete-user-form') --}}
                    </div>
                </div>
            @endif
        </div>
        
    </div>
</x-app-layout>
