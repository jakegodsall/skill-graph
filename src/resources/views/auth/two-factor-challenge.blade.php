<x-guest-layout>
    <div x-data="{ recovery: false, codeInputs: ['', '', '', '', '', ''] }" x-cloak>
        <div class="mb-4 text-base text-gray-700 font-medium" x-show="!recovery">
            {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
        </div>

        <div class="mb-4 text-base text-gray-700 font-medium" x-show="recovery">
            {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
        </div>

        <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-6">
            @csrf

            <!-- 6 individual input boxes for the code -->
            <div x-show="!recovery" class="mt-4">
                <x-label for="code" value="{{ __('Code') }}" class="text-sm text-gray-600 font-semibold" />
                <div class="mt-2 flex space-x-2">
                    <template x-for="(code, index) in codeInputs" :key="index">
                        <input 
                            x-model="codeInputs[index]"
                            type="text"
                            inputmode="numeric"
                            maxlength="1"
                            class="w-12 h-12 border border-gray-300 rounded-md text-center text-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            x-on:input="if ($event.target.value.length > 0) $event.target.nextElementSibling?.focus()"
                            x-on:keydown.backspace="$event.target.previousElementSibling?.focus()"
                        />
                    </template>
                </div>
                <input type="hidden" name="code" :value="codeInputs.join('')" />

                @error('code')
                <div class="text-red-600 text-sm mt-2 font-bold">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Recovery code form -->
            <div x-show="recovery" class="mt-4">
                <x-label for="recovery_code" value="{{ __('Recovery Code') }}" class="text-sm text-gray-600 font-semibold" />
                <x-input 
                    id="recovery_code" 
                    class="block mt-2 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                    type="text" 
                    name="recovery_code" 
                    x-ref="recovery_code" 
                    autocomplete="one-time-code" 
                />
            </div>
            <!-- Toggle buttons -->
            <div class="flex items-center justify-between mt-6">
                <button type="button" 
                    class="text-sm text-indigo-600 hover:text-indigo-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 no-underline"
                    x-show="!recovery"
                    x-on:click="
                        recovery = true;
                        $nextTick(() => { $refs.recovery_code.focus() })
                    ">
                    {{ __('Enter a recovery code') }}
                </button>

                <button type="button" 
                    class="text-sm text-indigo-600 hover:text-indigo-800 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 no-underline"
                    x-show="recovery"
                    x-on:click="
                        recovery = false;
                        $nextTick(() => { $refs.code.focus() })
                    ">
                    {{ __('Use an authentication code') }}
                </button>

                <x-success-button type="submit">
                    {{ __('Log in') }}
                </x-success-button>
            </div>
        </form>
    </div>
</x-guest-layout>
