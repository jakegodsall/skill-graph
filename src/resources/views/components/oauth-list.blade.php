<!-- OAuth -->
<div class="flex justify-center gap-4 mb-4">
    @foreach (config('oauth_providers.enabled') as $provider => $enabled)
        @if ($enabled)
            <x-oauth-button :provider="$provider" />
        @endif
    @endforeach
</div>