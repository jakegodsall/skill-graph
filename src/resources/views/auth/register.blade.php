<x-guest-layout>

    <h1 class="text-center text-2xl font-bold mb-2">Register</h1>
    <form method="POST" action="{{ route('register') }}" class="mb-3">
        @csrf
        <!-- Name -->
        <div class="mb-4">
            <x-label for="name" class="mb-1">
                {{ __('Username') }}
            </x-label>
            <x-input
                id="name"
                name="name"
                placeholder="Enter a username"
                value="{{ old('name') }}"
                required
                autocomplete="name"
            />
            @error('name')
                <x-validation-message>{{ $message }}</x-validation-message>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-label for="email" class="mb-1">
                {{ __('Email') }}
            </x-label>
            <x-input 
                id="email"
                type="email"
                name="email"
                placeholder="Enter an email address"
                value="{{ old('email') }}"
                required
                autocomplete="email"
            />
            @error('email')
                <x-validation-message>{{ $message }}</x-validation-message>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-label for="password" class="mb-1">
                {{ __('Password') }}
            </x-label>
            <x-password-input
                id="password"
                name="password"
                placeholder="Enter your password"
            />
            @error('password')
                <x-validation-message>{{ $message }}</x-validation-message>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <x-label for="password_confirmation" class="mb-1">
                {{ __('Confirm Password') }}
            </x-label>
            <x-password-input
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Confirm your password"
                required
            />
            @error('password_confirmation')
                <x-validation-message>{{ $message }}</x-validation-message>
            @enderror
        </div>

        <!-- Submit Button -->
        <x-submit-button type="submit">
            {{ __('Register') }}
        </x-submit-button>
    </form>

    <!-- OAuth -->
     <x-oauth-list />

    <a class="block text-center text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('login') }}">
        {{ __('Already registered?') }}
    </a>
</x-guest-layout>
