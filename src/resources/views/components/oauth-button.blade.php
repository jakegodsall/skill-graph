<a 
    href="{{ route('oauth.redirect', $provider) }}"
    class="text-sm p-4 aspect-square rounded-lg outline-none bg-tertiary-grey border-2 border-secondary-grey shadow-md cursor-pointer transition-transform transform duration-300 hover:scale-110"
>
    <i class="{{ config("oauth_providers.providers.$provider.icon") }} fa-xl"></i>
</a>