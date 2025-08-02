<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6 text-gray-700">
                    <h3 class="text-lg font-medium">{{ __("Welcome!") }}</h3>
                    <p class="mt-2 text-sm">
                        {{ __("You're logged in and ready to start.") }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
