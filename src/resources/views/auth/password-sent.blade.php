<x-guest-layout>
    <div>
        <h1 class="text-xl font-semibold text-gray-800 text-center mb-6">{{ __('Password Reset Link Sent') }}</h1>
        <p class="text-sm text-gray-600 text-center mb-6">
            {{ __('We have emailed your password reset link. Please check your inbox and follow the instructions to reset your password.') }}
        </p>
        <div class="flex justify-center mt-6">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 text-sm">
                {{ __('Return to Login') }}
            </a>
        </div>
    </div>
</x-guest-layout>
