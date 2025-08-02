<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>

    <div class="w-full flex flex-col sm:justify-center pt-2 items-center">
        <small class="text-gray-400">
            <span class="civersion">{{ 'b' . env('CI_BUILD_NUMBER', '0') }}&hyphen;{{ substr(env('CI_BUILD_COMMIT', 'local'), 0, 7) }}</span>
        </small>
    </div>
</div>
