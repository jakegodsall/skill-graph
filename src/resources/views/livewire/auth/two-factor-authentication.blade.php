<div x-data="{ showModal: false }" @disable-two-factor-completed.window="showModal = false">
    <h3 class="text-lg font-medium text-gray-900">
        {{ __("Two-Factor Authentication") }}
    </h3>
    <p class="mt-2 text-sm text-gray-600">
        {{ __("Secure your account by enabling two-factor authentication.") }}
    </p>

    <div class="mt-4">
        @if ($twoFactorEnabled) @if ($twoFactorConfirmed &&
        $recoveryCodesConfirmed)
        <p class="font-bold text-md text-green-600">
            {{ __("Two-factor authentication is currently enabled.") }}
        </p>
        @endif @if ($showingQrCode)
        <!-- Show QR Code -->
        <div class="mt-4">
            <p class="text-sm text-gray-600 font-semibold">
                {{ __("Scan this QR code with your authenticator app:") }}
            </p>
            <div class="mt-3 p-4 inline-block bg-gray-100 border rounded-lg">
                {!! $user->twoFactorQrCodeSvg() !!}
            </div>

            <!-- Confirm TOTP -->
            <div class="mt-4">
                <form wire:submit.prevent="confirmTwoFactorAuthentication">
                    <label
                        for="code"
                        class="block text-sm font-medium text-gray-700"
                        >{{
                            __("Enter OTP from your authenticator app")
                        }}</label
                    >
                    <input
                        type="text"
                        id="code"
                        wire:model="code"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                    @error('code')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <div class="mt-4">
                        <x-success-button type="submit">
                            {{ __("Confirm 2FA") }}
                        </x-success-button>
                        <x-danger-button
                            wire:click="disableTwoFactorAuthentication"
                            >Cancel</x-danger-button
                        >
                    </div>
                </form>
            </div>
        </div>
        @endif @if ($showingRecoveryCodes)
        <!-- Show Recovery Codes -->
        <div class="mt-4">
            <p class="text-sm text-gray-600 font-semibold">
                {{ __("Recovery Codes") }}
            </p>
            <p class="text-sm text-gray-600">
                {{
                    __(
                        "Keep these codes in a safe place. They can be used if you lose access to your authenticator app."
                    )
                }}
            </p>
            <div
                class="relative mt-3 grid gap-2 bg-gray-100 border p-3 rounded-md"
            >
                @foreach ($recoveryCodes as $code)
                <div class="flex justify-between items-center">
                    <span class="text-sm font-mono text-gray-800">{{
                        $code
                    }}</span>
                </div>
                @endforeach

                <button
                    wire:click="downloadAllRecoveryCodes"
                    class="absolute top-4 right-4 inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md shadow-sm hover:bg-gray-500 transition-all duration-300"
                >
                    <i class="fa-solid fa-download"></i>
                </button>
            </div>

            <!-- Confirmation Checkbox -->
            <div class="mt-4">
                <label for="confirmation" class="inline-flex items-center">
                    <input
                        type="checkbox"
                        id="confirmation"
                        wire:model.lazy="confirmationChecked"
                        class="form-checkbox h-5 w-5 text-indigo-600"
                    />
                    <span class="ml-2 select-none text-sm text-gray-700">{{
                        __("I have stored my recovery codes safely.")
                    }}</span>
                </label>
            </div>

            <!-- Proceed Button -->
            <div class="mt-4">
                <button
                    wire:click="proceedAfterRecoveryCodes"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 disabled:opacity-50"
                    @disabled(!$confirmationChecked)
                >
                    {{ __("Continue") }}
                </button>
            </div>
        </div>
        @endif @if($twoFactorConfirmed && $recoveryCodesConfirmed)
        <!-- Disable 2FA -->
        <div class="mt-6">
            <x-danger-button @click="showModal = true">
                {{ __("Disable Two-Factor Authentication") }}
            </x-danger-button>
        </div>
        @endif @else
        <!-- Enable 2FA -->
        <x-success-button wire:click="enableTwoFactorAuthentication">
            {{ __("Enable Two-Factor Authentication") }}
        </x-success-button>
        @endif
    </div>

    <!-- Disable 2FA Modal -->
    <div
        x-show="showModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        x-transition.opacity
    >
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">
                {{ __("Disable Two-Factor Authentication") }}
            </h2>
            <p class="text-sm text-gray-600 mb-4">
                {{
                    __(
                        "To disable two-factor authentication, please confirm your password."
                    )
                }}
            </p>

            <!-- Password Input -->
            <div class="mb-4">
                <label
                    for="password"
                    class="block text-sm font-medium text-gray-700"
                    >{{ __("Password") }}</label
                >
                <input
                    id="password"
                    type="password"
                    wire:model="password"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
                @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Modal Buttons -->
            <div class="flex justify-end space-x-4">
                <x-secondary-button @click="showModal = false">
                    {{ __("Cancel") }}
                </x-secondary-button>
                <x-danger-button wire:click="disableTwoFactorAuthentication">
                    {{ __("Disable 2FA") }}
                </x-danger-button>
            </div>
        </div>
    </div>
</div>
