<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <h1 class="text-center text-2xl font-bold mb-2">Login</h1>
    <form method="POST" action="{{ route('login') }}" class="mb-3">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <x-label for="email" class="mb-1">
                {{ __('Email') }}
            </x-label>
            <x-input
                id="email" 
                type="email" 
                name="email" 
                placeholder="Enter your email" 
                value="{{ old('email') }}" 
                required  
                autocomplete="username" />
            @error('email')
                <x-validation-message>{{ $message }}</x-validation-message>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <div class="flex justify-between items-end mb-1">
                <x-label for="password">
                    {{ __('Password') }}
                </x-label>
                <a class="text-xs text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            </div>
            {{-- <x-input id="password" 
                type="password" 
                name="password" 
                required
                autocomplete="current-password"
            /> --}}
            <x-password-input></x-password-input>
            @error('password')
                <x-validation-message>{{ $message }}</x-validation-message>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <x-submit-button button 
                type="submit" 
            >
                {{ __('Log in') }}
            </x-submit-button>
        </div>
    </form>

    <!-- OAuth -->
    <x-oauth-list />

    <a class="block text-center text-xs text-indigo-600 hover:text-indigo-500" href="{{ route('register') }}">
        {{ __('Need to register?') }}
    </a>
</x-guest-layout>
